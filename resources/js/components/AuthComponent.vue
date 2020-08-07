<template>        
        <div class="container">
            <!-- Authentication Links -->
            <div class="auth-inks" v-if="!user">
                <a class="nav-link" href="#" @click="showLoginForm = true; showRegForm = false">Login</a>
                <a class="nav-link" @click="showLoginForm = false; showRegForm = true" href="#">Register</a>
            </div>

            <div class="user-logged" v-if="user">
                <p>{{welcomeMessage}}</p>
            <a class="dropdown-item" :href="logoutRoute" @click='logout()'>Logout</a>
                <form id="logout-form" :action="logoutRoute" method="POST" style="display: none;">
                    <input type="hidden" name="_token" :value="csrf">
                </form>
            </div>
            

            <login-form :csrf="csrf" :loginRoute="loginRoute" v-if="showLoginForm"></login-form>
            <register-form @registered="setUser" :csrf="csrf" :registerRoute="registerRoute" v-if="showRegForm"></register-form>
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
        computed : {
            welcomeMessage() {
                return `Welcome, ${this.user['name']}!`;
            }
        },
        methods : {
            logout() {
                event.preventDefault();
                document.getElementById('logout-form').submit();
            },
            setUser(user) {
                this.user = user;
                this.showRegForm = this.showLoginForm = false;
            }
        },
    }
</script>