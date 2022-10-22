<template>
    <div>

        <div class="section">
            <div class="my-hero-container">
                <div class="columns is-centered">
                    <div class="column is-6">
                        <b-field>
                            <b-input type="text" expanded placeholder="Search Food here..."></b-input>
                            <p class="control">
                                <button class="button is-success">SEARCH</button>
                            </p>
                        </b-field>
                    </div>
                </div>
            </div>
        </div>

    </div> <!--root div-->
</template>

<script>
export default {
    props: ['propUser'],
    data(){
        return{
            locale: undefined,
            isModalActive: false,
        }

    },

    methods: {

    },

    mounted() {


    },


}
</script>

<style scoped>


    @media only screen and (max-width: 1024px) {

    }

    @media only screen and (max-width: 768px) {

    }

    @media only screen and (max-width: 480px) {


    }





</style>
