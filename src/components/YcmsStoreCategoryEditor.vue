<template>
    <div >
      <a class="small-rounded-btn blue-bg text-white" :href="'/app/'+this.app.slug+'/shop-categories'">Go Back</a>
        <div class="flex-row-top full-width">
            <input
                class="rounded-input"
                type="text"
                v-model="category.name"
                placeholder="Name of category"
                @change="changeName"
            />
            <ycms-image-uploader
                name="categoryImage"
                type="app-icon"
                :image-url="urlImage"
                :ratio="1"
                style="margin-right: 15px"
                size="43px"
                icon="app-image"
                v-gref:appicon
            ></ycms-image-uploader>
        </div>
        <div class="products">
            <a :href="'/app/'+this.app.slug+'/shop-products/'+category.id+'/create'" class="small-rounded-btn green-bg text-white" >Add product</a>
            <a :href="'/app/'+this.app.slug+'/shop-categories/'+category.id+'/filters'" class="small-rounded-btn green-bg text-white" >Edit filter</a>
            <ycms-store-products-list
                name="productsList"
                :products-list="category.products"
                :app="app"
            ></ycms-store-products-list>
        </div>
    </div>
</template>

<script>
    import YcmsStoreProductsList from "./YcmsStoreProductsList";
    export default {
        name: 'ycms-store-category-editor',
        components: {YcmsStoreProductsList},
        props: {
            shopCategory: Object,
            app: Object,
        },

        data() {
            return {
                category: this.shopCategory,
                categoryImage: null,
                urlImage: this.shopCategory.image?this.shopCategory.image.url_original: ''
            };
        },

        methods: {
            changeName() {
                post('/app/'+ this.app.slug +'/shop-categories/change-name', {
                    name: this.category.name,
                    id: this.category.id,
                })
                    .then(res => {
                        this.notifier.success('name changed success')
                    })
            },
        },
        mounted() {
            this.$root.$on('ls-change', (name, val) => {
                if (name == 'categoryImage'){

                    let formData = new FormData();
                    formData.append('image', val);
                    formData.append('id', this.category.id);
                    const config = {
                        headers: { 'content-type': 'multipart/form-data' }
                    };
                    post('/app/'+ this.app.slug +'/shop-categories/change-image', formData, config)
                        .then(res => {
                            this.notifier.success('image changed success')
                        })
                }
            })
        }
    };
</script>

<style lang="scss" scoped>

    .flex-row-top {

        justify-content: flex-start;
        margin: 42px 0 37px 0;
    }

    .round {

        width: 58px;
        height: 58px;
        border-radius: 29px;
        box-shadow: 0 10px 20px 0 rgba(0, 0, 0, 0.2);
        background-image: linear-gradient(225deg, #0997b1, #2ccae6);
        color: white;
        font-size: 35px;
        margin-right: 15px;
    }

    .rounded-input {
        margin-top: -45px;
        margin-left: 15px;
        font-size: 14px;
        font-weight: 300;
        color: #0997b1;
        width: 264px;
        height: 43px;
        border-radius: 22px;
        border: solid 1px #868686;
        background-color: white;
        margin-right: 22px;
        outline: none;
        padding-left: 15px;
    }

    ::placeholder {
        color: #0997b1;
        opacity: 1; /* Firefox */
    }

    .vue-js-switch {
        margin: 0;
        margin-right: 19px;
    }
    .products{
        margin-top: -200px;
    }

</style>
