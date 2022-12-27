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
                                        <option v-if="role === 'FACULTY'" value="DELIVER">DELIVER</option>
                                    </b-select>
                                </b-field>
                            </div>
                            <div class="mt-4" v-if="item.delivery_type === 'DELIVER'">
                                <b-select placeholder="Office"
                                          required v-model="item.office">
                                    <option v-for="(i, ix) in offices" :key="ix" :value="i.office">{{ i.office }}</option>
                                </b-select>
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
    props: ['propRole', 'propOffices'],

    data(){
        return {
            carts: [],

            errors: {},
            fields: {},
            role: '',
            offices: [],
        }
    },

    methods: {

        loadCarts(){
            axios.get('/get-cart-items').then(res=>{
                this.carts = res.data
            })
        },

        confirmPlaceOrder(item){
            //console.log(item)

            this.$buefy.dialog.confirm({
                title: 'Place Order?',
                type: 'is-info',
                message: 'Are you sure you want to place this order?',
                cancelText: 'Cancel',
                confirmText: 'Yes',
                onConfirm: () => this.placeOrder(item)
            });
        },

        placeOrder(item){
            this.fields = item;
            this.fields.store_user_id = item.product.store.user_id;

            axios.post('/place-cart-order', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Saved!',
                        type: 'is-success',
                        message: 'Order successfully placed.',
                    });
                    this.loadCarts()
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                    if(this.errors.delivery_type){
                        this.$buefy.dialog.alert({
                            title: 'Error!',
                            type: 'is-danger',
                            message: this.errors.delivery_type[0],
                        });
                    }
                }
            })
        },

        initData(){
            this.role = this.propRole
            this.offices = JSON.parse(this.propOffices)
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
