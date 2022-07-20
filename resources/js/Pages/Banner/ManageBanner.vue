<script setup> 
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>
<template>
    <AppLayout title="Manage Banner">
        <template #header>
            <div class="flex justify-between flex-wrap md:flex-nowwrap items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Banner</h2>

                <div v-if="!updatePositionMode">
                    <Link :href="route('addBanner')" class="btn btn-sm bg-green-400 border-none mr-1">Add New</Link>
                    <button @click="toggleUpdatePositionMode" class="btn bg-red-500 btn-sm border-none">Update Position</button>
                </div>

                <div v-if="updatePositionMode">
                    <button @click="toggleUpdatePositionMode" class="btn btn-sm border-none mr-1">Cancel</button>
                    <button @click="doneEditPosition" class="btn btn-sm bg-green-400 border-none">Done</button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
                    <table v-if="!updatePositionMode" class="table w-full">
                        <thead>
                            <tr class="text-left">
                                <th>
                                    Image
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
                            <tr v-for="item in banners" :key="item">
                                <td>
                                    <img :src="`${$baseUrl}/${item.bannerImageSrc}`" class="w-96 max-h-72 object-contain" alt="">
                                </td>
                                <td>
                                    <span class="text-gray-700 px-6 py-3 flex items-center text-xs" >{{ moment(item.updated_at).format("ddd MMM DD, YYYY [at] hh:mm a") }}</span>
                                </td>
                                <td>
                                    <button @click="deleteBanner(item.id)">
                                        <TrashIcon class="h-8 w-8 text-red-500" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- start of draggable table -->
                    <table v-if="updatePositionMode" class="table w-full">
                        <thead>
                            <tr class="text-left">
                                <th>
                                    &nbsp;
                                </th>
                                <th>
                                    Image
                                </th>
                                <th>
                                    Last Updated At
                                </th>
                            </tr>
                        </thead>

                        <draggable v-model="banners" tag="tbody" @start="drag=true" @end="drag=false">
                            <tr v-for="item in banners" :key="item.id">
                                <td scope="row">
                                    <DotsVerticalIcon class="h-8 w-8 cursor-pointer text-black"/>
                                </td>
                                <td>
                                    <img :src="`${$baseUrl}/${item.bannerImageSrc}`" class="w-96 max-h-72 object-contain" alt="">
                                </td>
                                <td>
                                    <span>{{ moment(item.updated_at).format("ddd MMM DD, YYYY [at] hh:mm a") }}</span>
                                </td>
                            </tr>
                        </draggable>
                    </table>
                    <!-- end of draggable table -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import moment from 'moment'
import { TrashIcon, DotsVerticalIcon } from '@heroicons/vue/outline'
import { VueDraggableNext } from 'vue-draggable-next'

export default {
    props: ['banners'],
    components: { 
        TrashIcon, 
        DotsVerticalIcon,
        draggable : VueDraggableNext,
    },
    data(){
        return {
            updatePositionMode : false,
            form: this.$inertia.form({
                bannerList : [],
            })
        }
    },
    mounted(){
        this.moment = moment
    },
    methods: {
        toggleUpdatePositionMode(){
            this.updatePositionMode = !this.updatePositionMode
        },
        doneEditPosition(){
            this.toggleUpdatePositionMode()
            // change position to new updated position
            for(var i=0; i<this.banners.length; i++){
                this.banners[i].bannerPosition = i
            }

            this.form.bannerList = this.banners
            
            this.form.post(this.route('updateBannerPosition'), {
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