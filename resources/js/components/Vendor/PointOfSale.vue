<template>
    <div>
        <div class="columns is-centered mt-5">
            <div class="column is-8">

                <div class="box">
                    <div class="box-header">
                        <div style="font-weight: bold; font-size: 1.2em; text-align: center; margin: 0 0 15px 0;">Point Of Sale</div>
                    </div><!--box header-->

                    <div class="columns">
                        <div class="column">
                            <b-field label-position="on-border" label="Store">
                                <b-select v-model="storeid">
                                    <option v-for="(item, index) in stores" :key="index"  :value="item.store_id">{{ item.store }}</option>
                                </b-select>
                            </b-field>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column">
                            <div class="table-pos">
                                <b-field label="Customer Name" label-position="on-border">
                                    <b-input type="text" v-model="fields.customer_name" placeholder="Customer Name" />
                                </b-field>

                                <modal-browse-product :prop-store-id="storeid" prop-product="product" @browseProduct="emitBrowseProduct($event)"></modal-browse-product>
                                <table>
                                    <tr>
                                        <th style="width: 70%;">Product</th>
                                        <th>Quantity</th>
                                        <th style="width: 200px;">Price</th>
                                    </tr>

                                    <tr v-for="(item, index) in fields.purchases" :key="index">
                                        <td>{{ item.product }}</td>
                                        <td><b-numberinput v-model="item.qty" placeholder="Quantity" min="1" :controls="false"></b-numberinput></td>
                                        <td>{{ (item.qty * item.price) | formatPrice }}</td>
                                    </tr>
                                    <tr style="border-top: 1px solid black; border-bottom: 1px solid black;">
                                        <td><strong>Total:</strong></td>
                                        <td></td>
                                        <td><strong>&#8369; <span>{{ totalPrice | formatPrice }}</span></strong></td>
                                    </tr>
                                </table>
                            </div><!-- tble pos -->
                        </div><!--col-->
                    </div> <!--cols -->

                    <div class="columns">
                        <div class="column">
                            <b-field label="Tendered Cash" label-position="on-border">
                                <b-numberinput :controls="false" size="is-large" v-model="fields.tendered_cash" @keyup.native.enter="calculateChange"></b-numberinput>
                            </b-field>

                            <b-field label="CHANGE" label-position="on-border">
                                <b-numberinput :controls="false" size="is-large" :editable="false" v-model="change"></b-numberinput>
                            </b-field>

                        </div>
                    </div> <!-- cols -->
                    <div class="buttons is-right my-4">
                        <b-button type="is-primary" @click="submit" label="Save Order"></b-button>
                    </div>

                </div><!--box-->

            </div><!--col -->
        </div><!--cols -->
    </div><!--root -->
</template>


<script>

export default{

    props: ['propStores'],
    data(){
        return{
            storeid: 0,
            stores: [],

            errors: {},

            fields: {
                customer_name: '',
                purchases: [],
            },
            totalAmount: 0,
            change: 0,

            product: '',
        }
    },

    methods: {
        initData(){
            this.stores = JSON.parse(this.propStores)
        },
        clearFields(){
            this.fields = {
                customer_name: '',
                    purchases: [],
                tendered_cash: 0,
                change: 0,
            };
            this.totalAmount = 0;
            this.change = 0;
        },
        emitBrowseProduct(row){
            if(row.qty < row.critical_level){
                this.$buefy.snackbar.open({
                    duration: 5000,
                    message: row.product + ' is ' + ' is below critical level.',
                })
            }

            this.fields.purchases.push({
                product_id: row.product_id,
                product: row.product,
                price: row.product_price,
                qty: 1,
                store_id: row.store_id
            });
        },

        submit(){
            this.calculateChange();

            axios.post('/vendor/pos', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Order Saved!',
                        message: 'Order successfully recorded.',
                        type: 'is-success',
                        onConfirm: () => {
                            this.clearFields()
                        }
                    })
                }
            }).catch(err =>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                    console.log(this.errors);
                    if(this.errors.product){
                        this.$buefy.dialog.alert({
                            title: 'Error!',
                            message: this.errors.product,
                            type: 'is-danger',
                        })
                    }
                    if(this.errors.tendered_cash){
                        this.$buefy.dialog.alert({
                            title: 'Error!',
                            message: this.errors.tendered_cash,
                            type: 'is-danger',
                        })
                    }

                }
            })
        },

        calculateChange(){
            this.change = this.fields.tendered_cash - this.totalAmount
            this.fields.change = this.change
        }
    },
    mounted(){
        this.initData()
    },

    computed: {
        totalPrice(){
            let total = 0;
            this.fields.purchases.forEach(item =>{
                total +=  (item.qty * item.price)
            })
            this.totalAmount = total
            return total;
        }
    }
}
</script>

<style scoped>

    .table-pos > table{

    }

    .table-pos > table tr td{
        padding: 10px;
    }

    .table-pos > table tr th{
        padding: 10px;
        border-bottom: 1px solid black;
    }

    .box-header{
        padding: 20px 0;
    }
</style>
