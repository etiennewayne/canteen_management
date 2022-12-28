<template>
    <div>
        <b-navbar>
            <template #brand>
                <b-navbar-item>
                    <img src="/img/logo.png">
                </b-navbar-item>
            </template>

            <template #start>


            </template>

            <template #end>
                <b-navbar-item tag="div">
                    <div class="buttons">
                        <b-button icon-right="logout" label="LOGOUT" @click="logout">
                        </b-button>
                    </div>

                </b-navbar-item>
            </template>
        </b-navbar>

        <div class="whole-page">
            <div class="page-text">
                Account not verified. Please verified the account first.
            </div>
        </div>
    </div>

</template>

<script>

export default {

    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/'
            })
        }
    }
}
</script>

<style scoped>

.whole-page{
    height: 100vh;
    margin-top: -25px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.page-text{
    font-weight: bold;
    font-size: 1.8em;
}
</style>
