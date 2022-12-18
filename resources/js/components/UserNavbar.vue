<template>
    <b-navbar>
        <template #brand>
            <b-navbar-item>
                <img src="/img/logo.png">
            </b-navbar-item>
        </template>

        <template #start>


        </template>

        <template #end>
             <b-navbar-item href="/">
                HOME
            </b-navbar-item>
            <!-- <b-navbar-item href="/about">
                ABOUT
            </b-navbar-item> -->
            <b-navbar-item href="/my-order" v-if="currentLogin">
                MY ORDER
            </b-navbar-item>
            <b-navbar-item tag="div">
                <div v-if="!currentLogin" class="buttons">
                    <a class="button is-primary" href="/sign-up">
                        <strong>Sign up</strong>
                    </a>
                    <a class="button is-light" href="/login">
                        Log in
                    </a>
                </div>
                <div v-else class="buttons">
                    <b-button label="LOGOUT" icon-left="logout" @click="logout">
                    </b-button>
                </div>
            </b-navbar-item>
        </template>
    </b-navbar>

</template>

<script>
export default {
    props: ['propUser'],

    data(){
        return{
            user: null,
        }
    },

    methods:{
        initData: function(){
            if(this.propUser){
                this.user = JSON.parse(this.propUser);
            }
        },

        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        }
    },

    mounted(){
        this.initData();
    },

    computed: {
        showName(){
            if(this.user){
                return this.user.fname.toUpperCase();
            }
            return '';
        },
        currentLogin(){
            return !!this.user;
        }
    }

}
</script>

<style>
    .navbar-item{
        font-weight: bold;
    }

    .navbar{
        border-bottom: 2px solid rgb(238, 93, 67);
    }
</style>
