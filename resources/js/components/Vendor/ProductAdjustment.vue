<template>
    <div>
        <div class="print-area">
            <div class="nprint">
                <div class="columns is-centered mt-5">
                    <div class="column is-8">
                        <b-field label="Date Filter" label-position="on-border">
                            <b-datepicker v-model="from"></b-datepicker>
                            <b-datepicker v-model="to"></b-datepicker>
                            <p class="controls">
                                <button class="button is-info" @click="loadAsyncData">
                                    <b-icon icon="magnify"></b-icon>
                                </button>
                            </p>
                        </b-field>
                    </div>
                </div>
                <div class="columns is-centered">
                    <div class="column is-8">
                        <!-- <b-field label="Store" label-position="on-border">
                            <b-select v-model="store">
                                <option v-for="(item, index) in stores" :key="index" :value="item.store">{{ item.store }}</option>
                            </b-select>
                            
                        </b-field> -->
                    </div>
                </div>
            </div>

            <div class="data-print">
                <div class="report-title-text">
                    <div>
                        Product Adjustment as of {{ new Date(from).toLocaleDateString() }} to {{  new Date(to).toLocaleDateString() }}
                    </div>
                    <div>
                        {{ store }}
                    </div>
                </div>
                <div class="table-report">
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Adjustment Type</th>
                            <th>Current Qty</th>
                            <th>Adjusted Qty</th>
                            <th>Inv Qty</th>
                            <th>Remarks</th>
                            <th>Date Adjusted</th>
                        </tr>
                        <tr v-for="(item, index) in data" :key="index">
                            <td>{{ item.product }}</td>
                            <td>{{ item.adjustment_type }}</td>
                            <td>{{ item.current_qty }}</td>
                            <td>{{ item.adjusted_qty }}</td>
                            <td>{{ item.qty }}</td>
                            <td>{{ item.remarks }}</td>
                            <td>{{ item.date_adjusted }}</td>
                        </tr>
                    </table>
                </div>

            </div>

        </div>



    </div>
</template>

<script>
    const now = new Date();

export default{
    props: ['propStores'],

    data() {
        return{
            data: [],
            from: new Date(now.getFullYear(), now.getMonth(), 1),
            to: new Date(now.getFullYear(), now.getMonth() + 1, 0),
            store: '',
            stores: [],
        }
    },

    methods: {

        loadAsyncData() {

            let nfrom = new Date(this.from);
            let nto = new Date(this.to);

            nfrom = nfrom.getFullYear() + '-' + (nfrom.getMonth() +  1) + '-' + nfrom.getDate()
            nto = nto.getFullYear() + '-' + (nto.getMonth() +  1) + '-' + nto.getDate()

            const params = [
                `from=${nfrom}`,
                `to=${nto}`,
            ].join('&')

            axios.get(`/vendor/get-adjustment-products?${params}`).then(res=>{
                this.data = res.data
            })
        },

        initData(){
            this.stores = JSON.parse(this.propStores)
        }

    },

    mounted() {
        //this.loadOffices();
        this.loadAsyncData();
        this.initData()

    }
}
</script>


<style>


</style>
