<template>
    <div>

        <div class="section">
            <div style="font-weight: bold; font-size: 1.8em;">{{ store.store }}</div>
        </div>

        <div class="section">
            <div class="product-head border-line">
                <div class="is-size-5 has-text-weight-bold">PRODUCTS</div>
                <button class="button is-small is-success" style="margin-left: auto;" @click="openModal">
                    <b-icon
                        icon="plus-box-outline"></b-icon>
                </button>
            </div>

            <div class="products">
                <div class="product" v-for="(item, index) in products.data" :key="index">
                    <div>
                        <div class="product-img-container">
                            <img :src="`/storage/products/${item.product_img_path}`" class="product-img" />
                        </div>
                        <span style="font-weight: bold">PRODUCT NAME: </span>

                        <span style="margin-left: 10px; font-weight: bold;">
                            <a :href="`/vendor/store-info/${item.product_id}`">{{ item.product }}</a>
                        </span>

                        <div style="display: flex;">
                            <div style="font-weight: bold; margin-right: 10px;">RATINGS:</div>
                            <b-rate
                                v-model="rate"
                                custom-text="4.6"></b-rate>
                        </div>
                        <div style="display: flex">
                            <div style="font-weight: bold; margin-right: 10px;">PRICE:</div>
                            <div>{{ item.product_price | formatPrice }}</div> <!--formatPrice format the number using js-->
                        </div>
                    </div>


                    <div class="buttons" style="margin-left: auto;">
                        <button class="is-small button is-warning" @click="getData(item.product_id)">
                            <b-icon
                                icon="pencil"></b-icon>
                        </button>
                        <button class="is-small button is-danger" @click="confirmDelete(item.product_id)">
                            <b-icon
                                icon="delete"></b-icon>
                        </button>
                    </div>
                </div><!--product-->

                    <b-pagination
                        :total="total"
                        v-model="current"
                        :per-page="perPage"
                        :icon-prev="prevIcon"
                        :icon-next="nextIcon"
                        aria-next-label="Next page"
                        aria-previous-label="Previous page"
                        aria-page-label="Page"
                        aria-current-label="Current page"
                        @change="onPageChange">
                    </b-pagination>

            </div>
        </div>


        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
            trap-focus
            :width="640"
            aria-role="dialog"
            aria-label="Modal"
            aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Product Information</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field>
                                        <b-checkbox v-model="fields.is_inv"
                                            true-value="1"
                                            false-value="0">
                                            Inventoriable
                                        </b-checkbox>
                                    </b-field>

                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Product" label-position="on-border"
                                             :type="this.errors.product ? 'is-danger':''"
                                             :message="this.errors.product ? this.errors.product[0] : ''">
                                        <b-input v-model="fields.product"
                                                 placeholder="Product" required>
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Price">
                                        <b-numberinput controls-alignment="left"
                                            v-model="fields.product_price" min="0"
                                            placeholder="Price"
                                            controls-position="compact"></b-numberinput>
                                    </b-field>
                                </div>
                            </div>
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Product Image"
                                            :type="this.errors.product_img_path ? 'is-danger':''"
                                            :message="this.errors.product_img_path ? this.errors.product_img_path[0] : ''">
                                        <b-upload v-model="fields.product_img" class="file-label">
                                            <span class="file-cta">
                                                <b-icon class="file-icon" icon="upload"></b-icon>
                                                <span class="file-label">Click to upload</span>
                                            </span>
                                                <span class="file-name" v-if="fields.product_img">
                                                {{ fields.product_img.name }}
                                            </span>
                                        </b-upload>
                                    </b-field>
                                </div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="isModalCreate=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->



    </div><!--root div -->
</template>


<script>
export default {
    props: ['propData'],
    data(){
        return{

            total: 0,
            loading: false,
            sortField: 'product_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',
            current: 1,

            prevIcon: 'chevron-left',
            nextIcon: 'chevron-right',

            store: {},
            products: [],
            errors: {},

            search: {
                product: ''
            },

            fields: {
                product: '',
                qty: 0,
                is_inv: 0,
                product_price: 0.00,
            },

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            isModalCreate: false,

            //sample only
            rate: 4.6,

        }
    },

    methods: {
        initData(){
            this.store = JSON.parse(this.propData);
            console.log(this.store);
            this.loadData();
        },


        loadData(){

            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `product=${this.search.product}`,
                `storeid=${this.store.store_id}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            axios.get(`/vendor/get-product-lists?${params}`).then(res=>{
                this.products = res.data;
                this.total = res.data.total
            })
        },
        onPageChange(page) {
            this.page = page
            this.loadData()
        },
        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadData()
        },

        setPerPage(){
            this.loadData()
        },

        openModal(){
            this.isModalCreate=true;
           this.clearFields();
            this.errors = {};
        },

        submit: function(){

            var formData = new FormData();
            formData.append('store_id', this.store.store_id);
            formData.append('product', this.fields.product ? this.fields.product : '');
            formData.append('qty', this.fields.qty);
            formData.append('is_inv', this.fields.is_inv);
            formData.append('product_price', this.fields.product_price);

            formData.append('product_img_path', this.fields.product_img ? this.fields.product_img : '');

            if(this.global_id > 0){
                //update
                axios.post('/vendor/my-products/'+this.global_id, formData).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/vendor/my-products', formData).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });
            }
        },


        clearFields: function(){
            this.fields = {
                product: '',
                qty: 0,
                is_inv: 0,
                product_price: 0.00
            };
        },



        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this?',
                cancelText: 'Cancel',
                confirmText: 'Delete',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/vendor/my-products/' + delete_id).then(res => {
                this.loadData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/vendor/my-products/'+ data_id).then(res=>{
                this.fields = res.data;
            });
        },

    },
    mounted() {
        this.initData();
    }
}
</script>

<style scoped>
.border-line{
    padding-bottom: 5px;
    border-bottom: 1px solid gray;
}

.product-head{
    display: flex;
}

.products{

}

.product{
    margin: 15px;
    -webkit-box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.71);
    -moz-box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.71);
    box-shadow: 0px 0px 2px 0px rgba(0,0,0,0.71);
    padding: 15px;
    display: flex;
}

.product-img-container{
    height: 150px;
    width: 150px;
    position:relative;
    overflow:hidden;
}
.product-img{
    position:absolute;
    top:0;
    bottom:0;
    margin: auto;
    width:100%;
}



.product-box{
    width: 200px;
    padding: 15px;
    margin: 15px;
    height: 200px;
    border: 1px solid green;
}

</style>
