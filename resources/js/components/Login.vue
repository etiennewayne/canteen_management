<template>
    <div class="login-wrapper">
        <div class="login">
            <form @submit.prevent="submit">
                <div class="box-login">
                    <div class="box-header">
                        <div class="title is-4">
                            <b-icon icon="login" class="sign-in-logo"></b-icon>
                            Sign In
                        </div>
                    </div>
                 

                    <div class="box-body">
                        <b-field label="Username" label-position="on-border"
                            :type="this.errors.username ? 'is-danger':''"
                            :message="this.errors.username ? this.errors.username[0] : ''">
                            <b-input type="text" v-model="fields.username" placeholder="Username" />
                        </b-field>

                        <b-field label="Password" label-position="on-border">
                            <b-input type="password" v-model="fields.password" password-reveal placeholder="Password" />
                        </b-field> 
                    </div>

                    <button class="box-button is-primary">Login</button>
                </div>
            </form>

        </div>
    </div>
</template>

<script>

export default {
    data(){
        return {
            fields: {
                username: null,
                password: null,
            },

            errors: {},
        }
    },

    methods: {
        submit: function(){
            axios.post('/login', this.fields).then(res=>{
                console.log(res.data)
                if(res.data.role === 'ADMINISTRATOR' || res.data.role === 'STAFF'){
                    window.location = '/admin-home';
                }
                if(res.data.role === 'CUSTOMER'){
                    window.location = '/';
                }
                if(res.data.role === 'VENDOR'){
                    window.location = '/vendor/dashboard';
                }

               //window.location = '/dashboard';
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            });
        }
    }
}
</script>


<style scoped src="../../css/login.css">
    
</style>
