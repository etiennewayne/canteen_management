<template>
    <div>
        <div class="section">

            <div class="procut-container">
                <div class="product-box">
                    <div class="product">
                        <div class="product-img">
                            <img :src="`/storage/products/${product.product_img_path}`" />
                        </div>
                        <div class="product-desc">
                            <div class="prod-title">
                                {{ product.product }}
                            </div>

                            <div class="prod-rating">
                                <b-rate v-model="rate"></b-rate>
                            </div>

                            <div class="prod-price">
                                <b-icon icon="currency-php"></b-icon>
                                {{ product.product_price  }}
                            </div>

                            <b-field label-position="on-border" label="Quantity" class="mt-5">
                                <b-numberinput min="0" v-model="fields.qty" controls-position="compact"></b-numberinput>
                            </b-field>

                            <b-field label-position="on-border" label="Delviery Type" class="mt-5" expanded
                                :type="this.errors.delivery_type ? 'is-danger':''"
                                :message="this.errors.delivery_type ? this.errors.delivery_type[0] : ''">
                                <b-select v-model="fields.delivery_type"
                                    placeholder="Select Delivery Type" expanded>
                                    <option value="PICK UP">PICK UP</option>
                                    <option v-if="role === 'FACULTY'" value="DELIVER">DELIVER</option>
                                </b-select>
                            </b-field>

                            <b-field label="Office" v-if="fields.delivery_type == 'DELIVER'" label-position="on-border" expanded
                                    :type="this.errors.office ? 'is-danger':''"
                                    :message="this.errors.office ? this.errors.office[0] : ''">
                                <b-select v-model="fields.office" placeholder="Select Office" expanded>
                                    <option v-for="(office, index) in offices" :key="index" :value="office.office">{{ office.office  }}</option>
                                </b-select>
                            </b-field>

                            <div class="prod-button">
                                <div class="buttons">
                                    <b-button type="is-primary" @click="buyNow" outlined label="Buy Now"></b-button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="store" v-if="product.store">
                        <div class="store-title">
                            <b-icon icon="storefront"></b-icon>
                            <div class="store-title-text">
                                {{  product.store.store }}
                            </div>
                        </div>
                        <div class="store-contact">
                            <b-icon icon="cellphone"></b-icon>
                            <div class="store-contact-text">
                                {{  product.store.contact_no }}
                            </div>
                        </div>
                        <div class="store-owner">
                            <b-icon icon="account"></b-icon>
                            <div class="store-owner-text">
                                {{  product.store.owner }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['propRole', 'propProductId', 'propProduct'],
    data() {
        return{
            productId: 0,
            product: {},
            role: '',
            rate: 4.8,
            fields: {
                qty: 1,
                customer_id: 0,
                owner_id: 0,
                price: 0,
                delivery_type: '',
                office: '',
            },
            errors: {},

            offices: [],
        }
    },

    methods: {

        clearFields(){
            this.fields = {
                qty: 1,
                customer_id: 0,
                owner_id: 0,
                price: 0,
                delivery_type: '',
                office: '',
            };
        },
        loadProduct(){
            axios.get('/get-product-detail/' + this.productId).then(res=>{
                this.product = res.data
            })
        },
        initData(){
            this.productId = parseFloat(this.propProductId)
            this.product = JSON.parse(this.propProduct)
            this.role = this.propRole;
        },

        buyNow(){

            this.role === 'CUSTOMER' ? this.fields.delivery_type = 'PICK UP' : '';
            this.fields.product_id = this.productId;
            this.fields.owner_id = this.product.store.user_id;
            this.fields.price = this.product.product_price;

            axios.post('/buy-now-store', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'ORDER PLACED!',
                        message: 'Your order was successfully placed.',
                        type: 'is-success',
                        onConfirm: () => {
                            this.clearFields();
                            window.location = '/my-order'
                        }
                    })
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;

                    if(this.errors.stock_out){
                        this.$buefy.dialog.alert({
                            title: 'Out of Stock!',
                            message: this.errors.stock_out[0],
                            type: 'is-danger'
                        })
                    }
                    if(this.errors.stock_over){
                        this.$buefy.dialog.alert({
                            title: 'Out of Stock!',
                            message: this.errors.stock_over[0],
                            type: 'is-danger'
                        })
                    }

                    //console.log(err.response.data.status)
                }
            })

            this.errors = {};
        },

        loadOffices(){
            axios.get('/get-offices').then(res=>{
                this.offices = res.data
            })

        }
    },

    mounted(){
        this.initData();
        this.loadOffices();
    }
}
</script>

<style src="../../css/buynow.css"></style>
