<template>
    <div>

        <div class="section">
            <div>{{ store.store }}</div>
        </div>

        <div class="section">
            <div class="product-head border-line">
                <div class="is-size-5 has-text-weight-bold">PRODUCTS</div>
                <button class="button is-small is-success" style="margin-left: auto;" @click="openStoreModal">
                    <b-icon
                        icon="plus-box-outline"></b-icon>
                </button>
            </div>

            <div class="products">
                <div class="product" v-for="(item, index) in products" :key="index">
                    <a :href="`/vendor/store-info/${item.product_id}`">{{ item.product }}</a>

                    <div class="buttons">
                        <button class="is-small button is-warning" @click="getData(item.product_id)">
                            <b-icon
                                icon="pencil"></b-icon>
                        </button>
                        <button class="is-small button is-danger" @click="confirmDelete(item.product_id)">
                            <b-icon
                                icon="delete"></b-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div><!--root div -->
</template>


<script>
export default {
    props: ['propData'],
    data(){
        return{
            store: {},

            products: [],

        }
    },

    methods: {
        initData(){
            this.store = JSON.parse(this.propData);
        }
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
    display: flex;
    flex-wrap: wrap;

}

.product-box{
    width: 200px;
    padding: 15px;
    margin: 15px;
    height: 200px;
    border: 1px solid green;
}
</style>
