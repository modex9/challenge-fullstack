<template>
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
                            <a class="nav-link" :href="loginRoute">Login</a>
                        </li>
                            <li class="nav-item">
                                <a class="nav-link" :href="registerRoute">Register</a>
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
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        name : 'AuthHeader',
        props : ['loginRoute', 'registerRoute', 'logoutRoute'],
        data : function () {
            return {
                csrf : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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