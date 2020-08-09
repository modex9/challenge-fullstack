<template>
    <div class="container" id="login">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form method="POST" @submit="login()">
                            <input type="hidden" name="_token" :value="csrf">

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input v-model="email" id="email" type="email" class="form-control" :class="{ 'is-invalid' : 'email' in errors}" name="email" value="" required autocomplete="email" autofocus>
                                    <span v-if="errors['email']" class="invalid-feedback" role="alert">
                                        <strong>{{ errors['email'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input v-model="password" id="password" type="password" class="form-control" :class="{ 'is-invalid' : 'password' in errors}" name="password" required autocomplete="current-password">
                                    <span v-if="errors['password']" class="invalid-feedback" role="alert">
                                        <strong>{{ errors['password'][0] }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input v-model="remember" class="form-check-input" type="checkbox" name="remember" id="remember">
                                        <label class="form-check-label" for="remember">Remember Me</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button name="github" @click="providerLogin" class="btn btn-secondary">Login with GitHub</button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button name="facebook" @click="providerLogin" class="btn btn-secondary">Login with Facebook</button>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                    <!-- @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name : 'LoginForm',
        props : ['csrf', 'loginRoute'],
        data : function () {
            return {
                email : '',
                password : '',
                remember : false,
                errors : {},
                loginProviders : ['github', 'facebook']
            }
        },
        methods : {
            login() {
                event.preventDefault();
                let data = {
                    email : this.email,
                    password : this.password
                };
                if(this.remember)
                    data['remember'] = 'on';
                this.$emit('toggle-load-overlay', true);
                fetch(this.loginRoute, {
                    method : 'POST',
                    body : JSON.stringify(data),
                    headers : {
                        "Content-Type": "application/json; charset=utf-8",
                        'X-CSRF-TOKEN' : this.csrf,
                    },
                })
                .then(data => data.json())
                .then(data => {
                    if(!data['success'])
                        this.errors = data['errors'];
                    else {
                        this.$emit('loggedin', data['user']);
                        this.$emit('update-token', data['csrf']);
                    }
                    this.$emit('toggle-load-overlay', false);
                })
                .catch(error => {
                    this.$emit('toggle-load-overlay', false);
                    console.log(error);
                });
            },
            providerLogin() {
                event.preventDefault();
                const providerName = event.target.getAttribute('name');
                if(!this.loginProviders.includes(providerName))
                    alert(`Provider ${providerName} is not supported.`);
                document.location = `${this.loginRoute}/${providerName}`;
            },
        }
    }
</script>