<!-- Profile Image -->
<div class="card card-primary card-outline">
    <div class="card-body box-profile">
        <div class="text-center">
            <img class="profile-user-img img-fluid img-circle"
                 src="{{ asset('img/profile_picture.jpeg') }}"
                 alt="User profile picture">
        </div>

        <h3 class="profile-username text-center">{{ Auth::user()->getFullName() }}</h3>

        <ul class="list-group list-group-unbordered mb-3">
            <li class="list-group-item">
                <b>{{ __('Translations') }}</b> <a
                    class="float-right">{{ Auth::user()->meanings->count() }}</a>
            </li>
            <li class="list-group-item">
                <b>{{ __('Points') }}</b> <a class="float-right">{{ Auth::user()->points }}</a>
            </li>
            <li class="list-group-item">
                <b>{{ __('Streak') }}</b> <a class="float-right">{{ Auth::user()->streak }}</a>
            </li>
            <li class="list-group-item">
                <b>{{ __('Multiplier') }}</b> <a class="float-right">{{ Auth::user()->multiplier }}x</a>
            </li>
        </ul>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
