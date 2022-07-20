<script setup> 
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <AppLayout title="Add Product">
        <template #header>
            <div class="flex justify-between flex-wrap md:flex-nowwrap items-center">
                <div class="flex">
                    <Link :href="route('manageProduct')">
                        <ChevronLeftIcon class="h-7 w-7 pb-1 cursor-pointer"/>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Product</h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg overflow-x-auto p-3">
                    <form @submit.prevent="addProduct" class="card pt-2 form-control">
                        <label class="label"><span class="label-text text-black">Name <span class="text-red-500">*</span></span></label>
                        <input v-model="form.productName" type="text" class="input input-md input-bordered w-full" required>
                        
                        <label class="label"><span class="label-text text-black">Category <span class="text-red-500">*</span></span></label>
                        <select v-model="form.productCategoryID" class="select select-bordered w-full" required>
                            <option v-for="category in productCategory" :key="category.id" :value="category.id">
                                {{ category.productCategoryName }}
                            </option>
                        </select>
                        
                        <label class="label"><span class="label-text text-black">Short Description <span class="text-red-500">*</span></span></label>
                        <input type="text" v-model="form.productShortDesc" class="input input-md input-bordered w-full" required>
                        
                        <label class="label"><span class="label-text text-black">Full Details</span></label>
                        <ckeditor class="text-xs" v-model="form.productFullDetails" :editor="editor"></ckeditor>

                        <label class="label"><span class="label-text text-black">Product Image <span class="text-red-500">*</span></span></label>
                        <DragDropImage @changed="handleProductImage" :max="1" clearAll="Clear All" :isRequired="true" maxError="Maximum one file only"/>
                        
                        <br>
                        <button class="btn bg-green-500 border-none mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { ChevronLeftIcon } from '@heroicons/vue/outline'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import DragDropImage from '../../components/DragDropImage.vue'

export default {
    props: ['productCategory'],
    components: { ChevronLeftIcon, DragDropImage },
    data(){
        return {
            editor : ClassicEditor,
            form: this.$inertia.form({
                productName: '',
                productCategoryID: '',
                productShortDesc: '',
                productFullDetails: '',
                productImageSrc: [],
            }),
        }
    },
    methods: {
        handleProductImage(files){
            this.form.productImageSrc = files[0]
        },
        addProduct(){
            this.form.post(this.route('addProduct.add'), {
                forceFormData: true,
                onSuccess: (response) => {
                    this.$swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon:  'success',
                        title: response.props.jetstream.flash.message
                    })
                }
            })
        },
    },
}
</script>

<style>

</style>