<template>
    <div>
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="buttons mt-5 is-right">
                    <b-button class="button is-primary" @click="selectAll">{{btnSelect}}</b-button>
                    <b-button class="button is-danger" icon-left="close" @click="deletePerCheck">Delete</b-button>

                </div>

                <div v-for="(item, index) in carts" :key="index">
                    <div class="item-container" v-if="item.product_img_path">
                        <div class="image-container">
                            <img :src="`/storage/products/${item.product_img_path}`" alt="">
                        </div>
                        <div class="item-info">
                            <div style="display: flex;">
                                <strong>Quantity:</strong>
                                <b-numberinput class="ml-3" size="is-small"
                                    controls-position="compact"
                                    min="1"
                                    v-model="item.qty"></b-numberinput>
                            </div>
                            <div>
                                <strong>Product:</strong>
                                {{  item.product }}
                            </div>
                            <div>
                                <strong>Price: </strong> {{  item.product_price | formatPrice }}
                            </div>
                            <div>
                                <strong>Product Qty: </strong> {{  item.product_qty }}
                            </div>
                            <div>
                                <b-field label="Delivery Type: ">
                                    <b-select placeholder="Delivery Type"
                                        v-model="item.delivery_type">
                                        <option value="PICK UP">PICK UP</option>
                                        <option v-if="role === 'FACULTY'" value="DELIVER">DELIVER</option>
                                    </b-select>
                                </b-field>
                            </div>
                            <div class="mt-4" v-if="item.delivery_type === 'DELIVER'">
                                <b-select placeholder="Office"
                                    required v-model="item.office">
                                    <option v-for="(i, ix) in offices"
                                        :key="ix"
                                        :value="i.office">
                                        {{ i.office }}
                                    </option>
                                </b-select>
                            </div>

                        </div>
                        <div class="item-buttons">
                            <b-checkbox v-model="item.is_place_order"
                                :true-value=1
                                :false-value=0
                            >Check to order</b-checkbox>
                            <!-- <b-button class="button is-primary is-small" @click="confirmPlaceOrder(item)">Place Order</b-button> -->
                            <b-button class="button is-danger is-small" icon-right="delete" @click="confirmRemoveCart(item)"></b-button>

                        </div>
                    </div>
                </div>
                <div class="cart-empty" v-if="carts.length < 1">
                    <div class="cart-empty-text">Cart is empty.</div>
                </div>


                <div class="buttons is-right">
                    <b-button class="button is-primary" @click="checkOut">Check Out</b-button>
                </div>
            </div> <!--col-->
        </div><!--cols-->




        <!--modal add to cart-->
        <b-modal v-model="modalCheckOut" has-modal-card
            trap-focus
            :width="640"
            aria-role="dialog"
            aria-label="Modal"
            aria-modal>


            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Purchase Summary</p>
                    <button
                        type="button"
                        class="delete"
                        @click="modalCheckOut = false"/>
                </header>

                <section class="modal-card-body">
                    <div class="">
                        <div class="columns">
                            <div class="column">
                                <div class="modal-item-container">
                                    <div>

                                        <div v-for="(item, ix) in fields.carts" :key="ix">
                                            <div class="checkout-item-container">
                                                <div class="checkout-image-container">
                                                    <img :src="`/storage/products/${item.product_img_path}`" alt="">
                                                </div>
                                                <div class="item-info">
                                                    <div>
                                                        <strong>Quantity: </strong> {{item.qty}}
                                                    </div>
                                                    <div>
                                                        <strong>Total Price: </strong> Php {{item.qty * item.product_price | formatPrice}}
                                                    </div>

                                                    <div>
                                                        <strong>Est. Delivery: </strong> {{ item.est_delivery }}
                                                    </div>
                                                </div>
                                            </div>



                                        </div> <!-- for loop -->
                                        <hr>
                                        <div class="checkout-total-price">
                                                <div class="checkout-total-price-text">
                                                    Total Price: {{ totalPriceOrder | formatPrice }}
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button
                        class="button is-primary"
                        @click="placeOrder"
                        label="Save">Place Order</button>
                </footer>
            </div>

        </b-modal>
        <!--close modal-->



    </div><!-- root div-->
</template>

<script>

export default{
    props: ['propRole', 'propOffices'],

    data(){
        return {
            carts: [],

            errors: {
                carts: [],
            },

            fields: {
                carts: [],
            },
            role: '',
            offices: [],

            modalCheckOut: false,

            toogle: 0,
            btnSelect: 'Select All'

        }
    },

    methods: {
        clearFields(){
            this.carts = [];
        },


        loadCarts(){
            axios.get('/get-cart-items').then(res=>{
                //this.clearFields()
                this.carts = [];
                res.data.forEach(el =>{
                    this.carts.push({
                        cart_id: el.cart_id,
                        contact_no: el.contact_no,
                        critical_level: el.critical_level,
                        is_available: el.is_available,
                        is_inv: el.is_inv,
                        is_place_order: 0,
                        owner: el.owner,
                        owner_id: el.owner_id,
                        product: el.product,
                        product_id: el.product_id,
                        product_img_path: el.product_img_path,
                        product_price: el.product_price,
                        product_qty: el.product_qty,
                        qty: el.qty,
                        store: el.store,
                        store_id: el.store_id,
                        time_consume: el.time_consume,
                        user_id: el.user_id,
                        delivery_type: this.role === 'STUDENT' ? 'PICK UP' : '',
                    });
                })

                console.log(this.carts.length);
                //this.carts = res.data
                
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



        placeOrder(){
            // this.fields = item;
            // this.fields.store_user_id = item.product.store.user_id;
            //this.fields.carts = this.carts

            console.log(this.fields.carts);

            if(this.fields.carts.length < 1){
                alert('Please select item in cart.');
                return;
            }

            axios.post('/place-cart-order', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Saved!',
                        type: 'is-success',
                        message: 'Order successfully placed.',
                    });
                    this.loadCarts()
                    this.modalCheckOut = false;
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

                    if(this.errors['carts.0.delivery_type']){
                        this.$buefy.dialog.alert({
                            title: 'Error!',
                            type: 'is-danger',
                            message: 'Please select delivery type.',
                        });
                    }
                }
            })
        },

        initData(){
            this.role = this.propRole
            this.offices = JSON.parse(this.propOffices)
        },

        //alert box ask for deletion
        confirmRemoveCart(item) {

            this.$buefy.dialog.confirm({
                title: 'Remove!',
                type: 'is-danger',
                message: 'Are you sure you want to remove this from card?',
                cancelText: 'Cancel',
                confirmText: 'Remove from cart',
                onConfirm: () => this.removeFromCart(item)
            });
        },

        removeFromCart(item){
            axios.post('/remove-cart-items/'+ item.cart_id).then(res=>{
                this.loadCarts();


            }).catch(err=>{

            })
        },


        checkOut(){
            //check out
            this.fields.carts = [];
            
            console.log(this.carts.length)

            this.carts.forEach(item=>{
                if(item.is_place_order || item.is_place_order > 0){

                    let delivery = new Date(new Date().getTime() + item.time_consume * 60000)

                    this.fields.carts.push({
                        cart_id: item.cart_id,
                        critical_level: item.critical_level,
                        delivery_type: item.delivery_type,
                        is_place_order: item.is_place_order,
                        product_img_path: item.product_img_path,
                        owner: item.owner,
                        owner_id: item.owner_id,
                        product_id:  item.product_id,
                        product: item.product,
                        product_price: item.product_price,
                        product_qty: item.product_qty,
                        qty: item.qty,
                        store: item.store,
                        store_id: item.store_id,
                        user_id: item.user_id,
                        est_delivery: delivery.toLocaleTimeString()
                    });
                }
            })

            console.log(this.fields.carts)

            if(this.fields.carts.length < 1){
                alert('Please select item in cart.');
                return;
            }

            this.modalCheckOut = true;
        },

        selectAll(){
           
            if(this.toogle == 0){
                this.carts.forEach(el=>{
                    el.is_place_order = 1
                });
                this.toogle = 1;
                this.btnSelect = 'Unselect All'
            }else{
                this.carts.forEach(el=>{
                    el.is_place_order = 0
                });
                this.toogle = 0;
                this.btnSelect = 'Select All'
            }
            
        },

        deletePerCheck(){
            axios.post('/remove-carts-item', this.carts).then(res=>{
                if(res.data.status == 'deleted')
                    this.$buefy.dialog.alert({
                        title: 'Removed!',
                        type: 'is-success',
                        message: 'Product removed.',
                        confirmText: 'Ok',
                    });
                this.loadCarts()
            })
        }

    },

    mounted(){
        this.initData()
        this.loadCarts();
    },


    computed: {
        totalPriceOrder(){
            let total = 0;
            this.fields.carts.forEach(el=>{
                total += el.qty * el.product_price
            });
            return total;
        }
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

    .cart-empty{
        display: flex;
        justify-content: center;
        margin: 25px;
    }

    .cart-empty-text{
        font-weight: bold;
        font-size: 1.1em;
        color: #aeaeae;
    }



    .checkout-item-container{
        padding: 15px;
        border: 1px solid #ffeeee;
        margin: 15px 5px;
        display: flex;
    }

    .checkout-image-container{
        height: 100px;
        width: 100px;
        position:relative;
        overflow:hidden;
    }


    .checkout-total-price{
        display: flex;
    }
    .checkout-total-price-text{
        margin-left: auto;
        font-weight: bold;
        font-size: 1.4em;
    }
</style>
