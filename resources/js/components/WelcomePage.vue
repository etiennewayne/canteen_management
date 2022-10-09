<template>
    <div>
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

    .my-hero-container{
        display: flex;
        flex-direction: column;
    }
    .main-text-container{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        margin: 0 25px 0 100px;
        z-index: 0;
        background: black;
        padding: 30px;
        opacity: .6;
    }


    @media only screen and (max-width: 1024px) {

    }

    @media only screen and (max-width: 768px) {
        .main-text-container{
            top: 230px;
            width: 100%;
            margin: 0;
        }
    }

    @media only screen and (max-width: 480px) {
        .main-text-container{
            padding: 15px;
        }
        .main-title{
            font-size: 1.2em;
        }

        .main-subtitle{
            font-size: .8em;
        }

    }





</style>
