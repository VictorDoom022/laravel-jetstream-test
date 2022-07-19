<script setup> 
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <AppLayout title="Add Product Category">
        <template #header>
            <div class="flex justify-between flex-wrap md:flex-nowwrap items-center">
                <div class="flex">
                    <Link :href="route('manageProductCategory')">
                        <ChevronLeftIcon class="h-7 w-7 pb-1 cursor-pointer"/>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Product Category</h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
                    
                    <form @submit.prevent="addProductCategory" class="card">
                        <h4 class="font-thin mb-2 text-black">Category Name</h4>
                        <input v-model="form.productCategoryName" type="text" class="input input-bordered w-full " required>
                        <br>
                        <button class="btn bg-green-500 w-full border-none">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { ChevronLeftIcon } from '@heroicons/vue/outline'

export default {
    components: { ChevronLeftIcon },
    data(){
        return {
            form: this.$inertia.form({
                productCategoryName: null,
            }),
        }
    },
    methods: {
        addProductCategory(){
            this.form.post(this.route('addProductCategory.add'), {
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
        }
    }
}
</script>

<style>

</style>