@include('partials.header', ["pageTitle" => "About Us"])
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="">
                <div class="table-responsive">
                    <table class="table project-list-table table-nowrap align-middle table-borderless">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($contacts as $contact)
                                <tr onclick="myFunction('{{$contact->name}}')">
                                    <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt=""
                                             class="avatar-sm rounded-circle me-2"/>
                                        {{$contact->name}}
                                    </td>
                                    <td><span class="badge badge-soft-success mb-0">{{$contact->phone}}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function myFunction(x) {
        console.log("data is:", x);
        window.Telegram.WebApp.sendData(JSON.stringify({"contact": x}));
    }
</script>
