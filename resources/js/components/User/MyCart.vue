<template>
    <div>
        <div class="columns is-centered">
            <div class="column is-8">

                <div v-for="(item, index) in carts" :key="index">
                    <div class="\item-container"></div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

export default{
    data(){
        return {
            carts: [],
        }
    },

    methods: {

        loadCarts(){
            axios.get('/get-cart-items').then(res=>{
                this.carts = res.data
            })
        }
    },

    mounted(){

    }
}
</script>

<style scoped>
    .item-container{
        padding: 15p[x];
        border: 1px solid #ffeeee;
    }
</style>