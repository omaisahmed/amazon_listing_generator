 <!-- Header -->
 <nav class="navbar navbar-expand-md navbar-dark bg-dark p-0">
     <div class="container-fluid">
        <a class="navbar-brand mx-auto" href="/">
            <img class="navbar-brand mx-auto" width="100" height="100" src="/images/logo.png">Amazon Listing Generator
        </a>
    </div>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
         aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarNav">
         <ul class="navbar-nav ml-auto">
             <!-- Authentication Links -->
             @guest
                 @if (Route::has('login'))
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                     </li>
                 @endif

                 @if (Route::has('register'))
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                     </li>
                 @endif
             @else
                 <li class="nav-item dropdown">
                     <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button"
                         data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                         {{ Auth::user()->name }}
                     </a>

                     <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                         <a class="dropdown-item" href="{{ route('logout') }}"
                             onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                             {{ __('Logout') }}
                         </a>

                         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                             @csrf
                         </form>
                     </div>
                 </li>
             @endguest
         </ul>
     </div>
 </nav>
