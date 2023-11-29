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
                                    <td>
{{--                                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2"/>--}}
                                        <div class="avatar-sm rounded-circle me-2 profile-image" data-name="{{$contact->name}}"></div>
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

    const hRange = [0, 360];
    const sRange = [50, 75];
    const lRange = [25, 60];

    const normalizeHash = (hash, min, max) => {
        return Math.floor((hash % (max - min)) + min);
    };

    const generateHSL = (name) => {
        const hash = getHashOfString(name);
        const h = normalizeHash(hash, hRange[0], hRange[1]);
        const s = normalizeHash(hash, sRange[0], sRange[1]);
        const l = normalizeHash(hash, lRange[0], lRange[1]);
        return [h, s, l];
    };

    const HSLtoString = (hsl) => {
        return `hsl(${hsl[0]}, ${hsl[1]}%, ${hsl[2]}%)`;
    };

    const getHashOfString = (str) => {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        hash = Math.abs(hash);
        return hash;
    };

    console.log('Color: ', HSLtoString(generateHSL('Mahdi'))); // 3206952792


    function myFunction(x) {
        console.log("data is:", x);
        window.Telegram.WebApp.sendData(JSON.stringify({"contact": x}));
    }

    const getRandomColor = () => {
        const h = Math.floor(Math.random() * 360);
        return `hsl(${h}deg, 90%, 85%)`;
    };


    $(function(){
        const list = {'a': '#ff0', 'b': '#eee', 'c': '#111'}
        const $el = $('.profile-image')
        // iterate every element that has the class .dynamic-color
        $.each($el, function () {
            const $this = $(this)
            // get the first letter
            // const firstLetter = $this.text().trim().charAt(0).toLowerCase();
            const firstLetter = $this.data('name').trim().charAt(0).toUpperCase();
            const word = $this.data('name');
            $this.text(firstLetter);
            // check if firstletter is a property of the list
            // if(list.hasOwnProperty(firstLetter)) {
                // if the property exist, get the corresponding color.
                $this.css({
                    // 'color' : HSLtoString(generateHSL(firstLetter)),
                    'background' : HSLtoString(generateHSL(word))
                })
            // }
        });

        $(window).scroll(function() {
            if($(window).scrollTop() == $(document).height() - $(window).height()) {
                // ajax call get data from server and append to the div
                console.log('end of screen!');
            }
        });

    });



</script>
