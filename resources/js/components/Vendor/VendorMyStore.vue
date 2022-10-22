<template>
    <div>
        <div class="section">
            <div class="store-head border-line">
                <div class="is-size-5 has-text-weight-bold">STORES</div>
                <button class="button is-small is-success" style="margin-left: auto;" @click="openStoreModal">
                    <b-icon
                        icon="plus-box-outline"></b-icon>
                </button>
            </div>

            <div class="stores">
                <div class="store-box" v-for="(item, index) in stores" :key="index">
                    <a :href="`/vendor/store-info/${item.store_id}`">{{ item.store }}</a>

                    <div class="buttons">
                        <button class="is-small button is-warning" @click="getData(item.store_id)">
                            <b-icon
                                icon="pencil"></b-icon>
                        </button>
                        <button class="is-small button is-danger" @click="confirmDelete(item.store_id)">
                            <b-icon
                                icon="delete"></b-icon>
                        </button>
                    </div>
                </div>



            </div>
        </div>


        <!--modal create-->
        <b-modal v-model="modalStore" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal
                 type = "is-link">

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">New/Update Store</p>
                        <button
                            type="button"
                            class="delete"
                            @click="modalStore = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">

                            <b-field label="Store"
                                     :type="this.errors.store ? 'is-danger':''"
                                     :message="this.errors.store ? this.errors.store[0] : ''">
                                <b-input type="text" v-model="fields.store" placeholder="Store" required />
                            </b-field>
                            <b-field label="Owner"
                                     :type="this.errors.owner ? 'is-danger':''"
                                     :message="this.errors.owner ? this.errors.owner[0] : ''">
                                <b-input type="text" v-model="fields.owner" placeholder="Owner" required />
                            </b-field>
                            <b-field label="Contact No."
                                     :type="this.errors.contact_no ? 'is-danger':''"
                                     :message="this.errors.contact_no ? this.errors.contact_no[0] : ''">
                                <b-input type="text" v-model="fields.contact_no" placeholder="Contact No." required />
                            </b-field>

                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <b-button
                            label="Close"
                            @click="modalStore=false"/>
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->


    </div>
</template>

<script>
    export default{
        data(){
            return {

                id: 0,

                stores: [],

                fields: {},
                errors: {},

                modalStore: false,

                btnClass: {
                    'is-success': true,
                    'button': true,
                    'is-loading':false,
                },
            }
        },

        methods: {
            loadStores(){
                axios.get('/vendor/get-my-stores').then(res=>{
                    this.stores = res.data;
                })
            },

            clearFields(){
                this.fields = {};
            },

            openStoreModal(){
                this.clearFields();
                this.modalStore = true;
            },


            submit: function(){
                if(this.id > 0){
                    //update
                    axios.put('/vendor/my-store/'+this.id, this.fields).then(res=>{
                        if(res.data.status === 'updated'){
                            this.$buefy.dialog.alert({
                                title: 'UPDATED!',
                                message: 'Successfully updated.',
                                type: 'is-success',
                                onConfirm: () => {
                                    this.loadStores();
                                    this.clearFields();
                                    this.id = 0;
                                    this.modalStore = false;
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
                    axios.post('/vendor/my-store', this.fields).then(res=>{
                        if(res.data.status === 'saved'){
                            this.$buefy.dialog.alert({
                                title: 'SAVED!',
                                message: 'Successfully saved.',
                                type: 'is-success',
                                confirmText: 'OK',
                                onConfirm: () => {
                                    this.modalStore = false;
                                    this.loadStores();
                                    this.clearFields();
                                    this.id = 0;
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



            //alert box ask for deletion
            confirmDelete(delete_id) {
                this.$buefy.dialog.confirm({
                    title: 'DELETE!',
                    type: 'is-danger',
                    message: 'Are you sure you want to delete this data?',
                    cancelText: 'Cancel',
                    confirmText: 'Delete',
                    onConfirm: () => this.deleteSubmit(delete_id)
                });
            },
            //execute delete after confirming
            deleteSubmit(delete_id) {
                axios.delete('/vendor/my-store/' + delete_id).then(res => {
                    this.loadStores();
                }).catch(err => {
                    if (err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                });
            },
            getData: function(data_id){
                this.clearFields();
                this.id = data_id;
                this.modalStore = true;


                //nested axios for getting the address 1 by 1 or request by request
                axios.get('/vendor/my-store/' + data_id).then(res=>{
                    this.fields = res.data;
                });
            },


        },

        mounted() {
            this.$nextTick(function(){
                this.loadStores();
            })
        }
    }
</script>


<style scoped>
    .border-line{
        padding-bottom: 5px;
        border-bottom: 1px solid gray;
    }

    .store-head{
        display: flex;
    }

    .stores{
        display: flex;
        flex-wrap: wrap;

    }

    .store-box{
        width: 200px;
        padding: 15px;
        margin: 15px;
        height: 200px;
        border: 1px solid green;
    }
</style>
