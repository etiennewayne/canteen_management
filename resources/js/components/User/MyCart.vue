<template>
    <div>
        <div class="columns is-centered">
            <div class="column is-8">

                <div v-for="(item, index) in carts" :key="index">
                    <div class="item-container">
                        <div class="image-container">
                            <img :src="`/storage/products/${item.product.product_img_path}`" alt="">
                        </div>
                        <div class="item-info">
                            <div style="display: flex;">
                                <strong>Quantity:</strong> 
                                <b-numberinput class="ml-3" size="is-small" controls-position="compact" min="1"  v-model="item.qty"></b-numberinput>
                            </div>
                            <div>
                                <strong>Product:</strong> 
                                {{  item.product.product }}
                            </div>
                            <div>
                                <strong>Price: </strong> {{  item.price | formatPrice }}
                            </div>
                            <div>
                                <b-field label="Delivery Type: ">
                                    <b-select placeholder="Delivery Type" 
                                        required v-model="item.delivery_type">
                                        <option value="PICK UP">PICK UP</option>
                                        <option value="DELIVER">DELIVER</option>
                                    </b-select>
                                </b-field>
                            </div>
                            
                        </div>
                        <div class="item-buttons">
                            <b-button class="button is-primary is-small" @click="confirmPlaceOrder(item)">Place Order</b-button>
                        </div>
                    </div>
                </div>

            </div>
        </div><!--cols-->


        
    </div><!-- root div-->
</template>

<script>

export default{
    props: ['propRole'],

    data(){
        return {
            carts: [],

            errors: {},
            fields: {},
            role: '',
        }
    },

    methods: {

        loadCarts(){
            axios.get('/get-cart-items').then(res=>{
                this.carts = res.data
            })
        },

        confirmPlaceOrder(item){
            console.log(item)

            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to place this order?',
                cancelText: 'Cancel',
                confirmText: 'Yes',
                onConfirm: () => this.placeOrder(item)
            });
        },

        placeOrder(item){
            this.fields = item;

            axios.post('/place-cart-order', this.fields).then(res=>{
            
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                }
            })
        },

        initData(){
            this.role = this.propRole
        }

    },

    mounted(){
        this.initData()
        this.loadCarts();
    }
}
</script>

<style scoped>
    .item-container{
        padding: 15px;
        border: 1px solid #ffeeee;
        margin: 15px 5px;
        padding: 15px;
        display: flex;
    }

    .image-container{
        height: 150px;
        width: 150px;
        position:relative;
        overflow:hidden;
    }

    .item-info{
        margin: 5px 15px;
    }

    .item-buttons{
        margin-left: auto;
    }
</style>