<script setup> 
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>
<template>
    <AppLayout title="Add Product Category">
        <template #header>
            <div class="flex justify-between flex-wrap md:flex-nowwrap items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Product</h2>
                <Link :href="route('addProduct')" class="btn btn-sm bg-green-400 border-none">Add New</Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
                    <table class="table w-full">
                        <thead>
                            <tr class="text-left">
                                <th>
                                    Name
                                </th>
                                <th>
                                    Category Name
                                </th>
                                <th>
                                    Last Updated At
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in product" :key="item">
                                <td>
                                    <span>{{ item.productName }}</span>
                                </td>
                                <td>
                                    <span>{{ item.productCategoryName }}</span>
                                </td>
                                <td>
                                    <span>{{ moment(item.updated_at).format("ddd MMM DD, YYYY [at] hh:mm a") }}</span>
                                </td>
                                <td>
                                    <button class="px-2">
                                        <Link :href="route('editProduct', {'productID' : item.id})">
                                            <PencilAltIcon class="h-8 w-8 text-yellow-500" />
                                        </Link>
                                    </button>
                                    <button @click="deleteProduct(item.id)" class="px-2">
                                        <TrashIcon class="h-8 w-8 text-red-500" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import moment from 'moment'
import { TrashIcon, PencilAltIcon } from '@heroicons/vue/outline'

export default {
    props: ['product'],
    components: { TrashIcon, PencilAltIcon },
    mounted(){
        this.moment = moment
    },
    methods: {
        async deleteProduct(productID){
            var result = await this.$swal.fire({
                title: 'Are you sure you want to delete this category?',
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes',
                showCancelButton: true,
            })

            if(!result.isDismissed){
                // delete 
                this.$inertia.delete(route('deleteProduct', {'productID' : productID}), {
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
        },
    }
}
</script>

<style>

</style>