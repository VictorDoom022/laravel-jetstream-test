<script setup> 
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>
<template>
    <AppLayout title="Add Banner">
        <template #header>
            <div class="flex justify-between flex-wrap md:flex-nowwrap items-center">
                <div class="flex">
                    <Link :href="route('manageBanner')">
                        <ChevronLeftIcon class="h-7 w-7 pb-1 cursor-pointer"/>
                    </Link>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Banner</h2>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-3">
                    <form @submit.prevent="uploadBanner">
                        <DragDropImage @changed="handleImage" :max="1" class="text-black" clearAll="Clear All" :isRequired="true" maxError="Maximum one file only"/>
                        <br>
                        <button class="btn bg-green-500 w-full border-none">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import DragDropImage from '../../components/DragDropImage.vue'
import { ChevronLeftIcon } from '@heroicons/vue/outline'

export default {
    props: ['banners'],
    components: { 
        DragDropImage,
        ChevronLeftIcon
    },
    data(){
        return {
            updatePositionMode : false,
            form: this.$inertia.form({
                bannerImage : [],
            })
        }
    },
    methods: {
        handleImage(files){
            this.form.bannerImage = files[0]
        },
        uploadBanner(){
            this.form.post(this.route('addBanner.add'), {
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
    }
}
</script>

<style>

</style>