<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

//use App\Models\ResetCodePassword;
use App\Models\User;
use Brick\VarExporter\ExportException;
use Brick\VarExporter\VarExporter;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function forgot(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where("email", $request->email)->with("groups")->first();

//        var_dump($user);
//        return ['message' => $user];
//        return response(["user" => $user]);
        if(!isset($user) || empty($user)){
            return response(['message' => "You are not currently invited to this event"], 403);
        }

        // Delete all old code that user send before.
//        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(10000000, 99999999);

        // Create a new code
//        $codeData = ResetCodePassword::create($data);

        $client = new Client([
            'base_uri' => "https://898dnr.api.infobip.com/",
            'headers' => [
                'Authorization' => "App " . env('INFOBIP_API_KEY', 'aafe76af42a35cee16b006f3b07b1350-6060cd84-8fc8-4c5e-a78f-6b2ba461c64a'),
                'Content-Type' => 'multipart/form-data',
                'Accept' => 'application/json',
            ]
        ]);

        $response = $client->request(
            'POST',
            'email/2/send',
            [
                RequestOptions::MULTIPART => [
                    ['name' => 'from', 'contents' => "amuse@selfserviceib.com"],
                    ['name' => 'to', 'contents' => $data['email']],
                    ['name' => 'subject', 'contents' => 'Your confirmation code'],
//                    ['name' => 'text', 'contents' => 'Here is your Should Meet confirmation code: ' . $codeData->code],
                ],
            ]
        );

        return response(['message' => "Confirmation code is sent to your email"], 200);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
//            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:8',
            'fcm_token' => 'required|string|min:8',
        ]);


//        $passwordReset = ResetCodePassword::where('code', $request->code)->where('email', $request->email)->first();

        // check if it does not expired: the time is one hour
        if (!isset($passwordReset) || empty($passwordReset)) {
            return response(['message' => 'Confirm code is invalid'], 422);
        }

        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => "Confirm code has expired"], 422);
        }

        $user = User::firstWhere('email', $passwordReset->email);

//        $user->update($request->only('password'));
        $user->update([
            'fcm_token' => $request->fcm_token,
            'password'=>Hash::make($request->password)
        ]);

        $passwordReset->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $request->user()
        ]);

//        return response(['message' =>'Password has been successfully reset'], 200);

//        $user = User::create([
//            'name' => $validatedData['name'],
//            'email' => $validatedData['email'],
//            'password' => Hash::make($validatedData['password']),
//        ]);
//
//        $token = $user->createToken('auth_token')->plainTextToken;

//        return response()->json([
//            'access_token' => $token,
//            'token_type' => 'Bearer',
//        ]);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
            'fcm_token' => $request->fcm_token,
        ]);


        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $request->user()
        ]);
    }

    public function me(Request $request)
    {
        return $request->user();
    }


    /**
     * @throws ExportException
     */
    public function updateUserConfig(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
//        $validatedData = $request->validate([
//            'file' => 'required|image|mimes:jpg,webp,png,jpeg,gif,svg|max:2048',
//        ]);

//        return response($request->file('file'));
//        $validatedData = $request->validated();
//        if($request->file('file')) {
//            $validatedData['image'] = $request->file('file')->store('/images');
//            $validatedData['url'] = Storage::url($validatedData['image']);
//            $request->user()->avatar = Storage::url($validatedData['image']);
//        }

//        config(['bots.test_field' => 'this is a test']);
//        $fp = fopen(base_path() .'/config/telegram.php' , 'w');
//        fwrite($fp, '<?php return ' . var_export(config('bots'), true) . ';');
//        fclose($fp);
//        Config::write('app.url', 'http://domain.com');

        $this->update($request);

        $request->user()->chat_id = substr($request->chat_id, 0, 255);
        $request->user()->bot_token = substr($request->token, 0, 255);
        $request->user()->save();

        return response($request->user(), Response::HTTP_CREATED);
    }

    /**
     * @throws ExportException
     */
    public function update(Request $request){

//        $values = $request->validate([
//            "support_mail" => ['required', 'email:rfc,dns'],
//            "api_endpoint" => ['required','URL']
//        ]);

        $config = (object) config('telegram');
        $bot = [
            'token' => $request->token,
            'webhook_url' => env('TELEGRAM_WEBHOOK_URL', 'https://sms.tikoagency.ir/callback') . '/user_' . $request->user()->id,
        ];

        $config->bots['user_' . $request->user()->id] = $bot;

        $newConfig = VarExporter::export(
            (array) $config,
            VarExporter::INLINE_SCALAR_LIST | VarExporter::ADD_RETURN
        );

        if( File::put(base_path() . '/config/telegram.php', "<?php\n\n". $newConfig) ) {

            Artisan::call('config:clear');

            return back()->with('message', 'Settings updated');

        }

        return true;
    }

}
