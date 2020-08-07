<template>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" @click="showLoginForm = true; showRegForm = false">Login</a>
                        </li>
                            <li class="nav-item">
                                <a class="nav-link" @click="showLoginForm = false; showRegForm = true" href="#">Register</a>
                            </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" :href="logoutRoute" @click='logout()'>
                                    Logout
                                </a>

                                <form id="logout-form" :action="logoutRoute" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" :value="csrf">
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <login-form :csrf="csrf" :loginRoute="loginRoute" v-if="showLoginForm"></login-form>
        <register-form :csrf="csrf" :registerRoute="registerRoute" v-if="showRegForm"></register-form>
    </div>
</template>

<script>
    import LoginForm from "./LoginForm";
    import RegisterForm from "./RegisterForm";

    export default {
        name : 'AuthHeader',
        props : ['loginRoute', 'registerRoute', 'logoutRoute'],
        components : {
            LoginForm, RegisterForm
        },
        data : function () {
            return {
                csrf : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                showLoginForm: false,
                showRegForm: false,
                user : null,
            }
        },
        methods : {
            logout() {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            }
        },
    }
</script>