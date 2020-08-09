<template> 
        <div class="container">
            <loading :active.sync="isLoading" :can-cancel="false" :is-full-page="true" color="blue" loader='dots' background-color='#bfd5e4'></loading>
            <!-- Authentication Links -->
            <div class="auth-inks" v-if="!user">
                <a class="nav-link" href="#" @click="showLoginForm = true; showRegForm = false">Login</a>
                <a class="nav-link" @click="showLoginForm = false; showRegForm = true" href="#">Register</a>
            </div>

            <div class="user-logged" v-if="user">
                <p>{{welcomeMessage}}</p>
                <a href="#" @click='logout()'>Logout</a>
            </div>

            <login-form @toggle-load-overlay='toggleLoadOverlay' @loggedin="setUser" @update-token="updateToken"  :csrf="csrf" :loginRoute="loginRoute" v-if="showLoginForm"></login-form>
            <register-form @toggle-load-overlay='toggleLoadOverlay' @registered="setUser" :csrf="csrf" :registerRoute="registerRoute" v-if="showRegForm"></register-form>
            <comments-component @show-login="showLoginForm = true; showRegForm = false" :user="user" :get-comments-route="getCommentsRoute" :save-comment-route="saveCommentRoute"></comments-component>
        </div>
</template>

<script>
    import LoginForm from "./LoginForm";
    import RegisterForm from "./RegisterForm";
    import Loading from 'vue-loading-overlay';
    import CoommetnsComponent from "./CommentsComponent";
    export default {
        name : 'AppComponent',
        props : ['loginRoute', 'registerRoute', 'logoutRoute', 'sessionUser', 'showLoginForm', 'getCommentsRoute', 'saveCommentRoute'],
        components : {
            LoginForm, RegisterForm, Loading, CoommetnsComponent
        },
        data : function () {
            return {
                csrf : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                showRegForm: false,
                user : !this.sessionUser ? null : JSON.parse(this.sessionUser),
                isLoading : false,
            }
        },
        created() {
            this.showLoginForm = false;
        },
        computed : {
            welcomeMessage() {
                return `Welcome, ${this.user['name']}!`;
            }
        },
        methods : {
            logout() {
                this.isLoading = true;
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
                        this.updateTokenDOM();
                    }
                })
                .catch(error => console.log(error))
                .finally(() => this.isLoading = false);
            },
            setUser(user) {
                this.user = user;
                this.showRegForm = this.showLoginForm = false;
            },
            updateToken(token) {
                this.csrf = token;
                this.updateTokenDOM();
            },
            updateTokenDOM() {
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', this.csrf);
            },
            toggleLoadOverlay(displayOverlay) {
                this.isLoading = displayOverlay;
            },
        },
    }
</script>
<style lang="css">
    @import '/node_modules/vue-loading-overlay/dist/vue-loading.css';
</style>