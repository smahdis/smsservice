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
                            <th scope="col">Position</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr onclick="myFunction('Simon Ryles')">
                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" />
                                Simon Ryles
                            </td>
                            <td><span class="badge badge-soft-success mb-0">Full Stack Developer</span></td>
                        </tr>
                        <tr onclick="myFunction('Marion Walker')">
                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" />
                                Marion Walker
                            </td>
                            <td><span class="badge badge-soft-info mb-0">Frontend Developer</span></td>
                        </tr>
                        <tr onclick="myFunction('Frederick White')">

                            <td>
                                <div class="avatar-sm d-inline-block me-2">
                                    <div class="avatar-title bg-soft-primary rounded-circle text-primary"><i class="mdi mdi-account-circle m-0"></i></div>
                                </div>
                                Frederick White
                            </td>
                            <td><span class="badge badge-soft-danger mb-0">UI/UX Designer</span></td>
                        </tr>
                        <tr onclick="myFunction('Shanon Marvin')">
                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" />
                                Shanon Marvin
                            </td>
                            <td><span class="badge badge-soft-primary mb-0">Backend Developer</span></td>
                        </tr>
                        <tr onclick="myFunction('Mark Jones')">
                            <td>
                                <div class="avatar-sm d-inline-block me-2">
                                    <div class="avatar-title bg-soft-primary rounded-circle text-primary"><i class="mdi mdi-account-circle m-0"></i></div>
                                </div>
                                Mark Jones
                            </td>
                            <td><span class="badge badge-soft-info mb-0">Frontend Developer</span></td>
                        </tr>
                        <tr onclick="myFunction('Janice Morgan')">
                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" />
                                Janice Morgan
                            </td>
                            <td><span class="badge badge-soft-primary mb-0">Backend Developer</span></td>
                        </tr>
                        <tr onclick="myFunction('Patrick Petty')">
                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" />
                                Patrick Petty
                            </td>
                            <td><span class="badge badge-soft-danger mb-0">UI/UX Designer</span></td>
                        </tr>
                        <tr onclick="myFunction('Marilyn Horton')">
                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" />
                                Marilyn Horton
                            </td>
                            <td><span class="badge badge-soft-primary mb-0">Backend Developer</span></td>
                        </tr>
                        <tr onclick="myFunction('Neal Womack')">
                            <td><img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="avatar-sm rounded-circle me-2" />
                                Neal Womack
                            </td>
                            <td><span class="badge badge-soft-success mb-0">Full Stack Developer</span></td>
                        </tr>
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
