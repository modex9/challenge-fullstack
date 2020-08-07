<template>        
        <div class="container">
            <!-- Authentication Links -->
            <div class="auth-inks" v-if="!user">
                <a class="nav-link" href="#" @click="showLoginForm = true; showRegForm = false">Login</a>
                <a class="nav-link" @click="showLoginForm = false; showRegForm = true" href="#">Register</a>
            </div>

            <div class="user-logged" v-if="user">
                <p>{{welcomeMessage}}</p>
                <a href="#" @click='logout()'>Logout</a>
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
                fetch(this.logoutRoute, {
                    method : 'POST',
                    headers : {
                        "Content-Type": "application/json; charset=utf-8",
                        'X-CSRF-TOKEN' : this.csrf,
                    },
                })
                .then(data => data.json())
                .then(data => {
                    if(data['success']) {
                        this.user = null;
                        this.csrf = data['csrf'];
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', this.csrf);

                    }
                })
                .catch(error => console.log(error));
            },
            setUser(user) {
                this.user = user;
                this.showRegForm = this.showLoginForm = false;
            }
        },
    }
</script>