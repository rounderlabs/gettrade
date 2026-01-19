<template>


    <section class="section-b-space">
        <div class="custom-container">
            <ul class="pagination justify-content-center">
                <li :class="[!pageUrls.prev ? 'disabled':'']" class="page-item disabled">
                    <a class="page-link" href="javascript:void(0)"  @click="request(pageUrls.prev)" >Prev</a>
                </li>
                <li class="page-item"><a class="page-link active" href="javascript:void(0)">{{ pageUrls.current }}</a></li>
                <li class="page-item" :class="[!pageUrls.next ? 'disabled':'']">
                    <a class="page-link" href="javascript:void(0)"  @click="request(pageUrls.next)">Next</a>
                </li>
            </ul>
        </div>
    </section>


</template>

<script>
import {onMounted, ref} from "vue";


export default {
    name: "Paginator",
    props: {
        baseUrl: {
            type: String,
            default: null
        },
        onMountedRequest: {
            type: Boolean,
            default: true
        }

    },
    emits: ['responseData','pageMeta'],
    // watch: {
    //     'items': function(){
    //         this.update(0);
    //     }
    // },
    setup(props, context) {
        const pageUrls = ref({
            prev: null,
            next: null,
            current: null,
            perPage: null,
            from: null,
            to:null
        })


        function emitResponse(responseData) {
            context.emit('responseData', responseData)
        }

        function emitPageMeta(){
            context.emit('pageMeta',pageUrls.value)
        }

        function request(url) {
            if (url) {
                axios.get(url).then(res => {
                    let response = res.data
                    pageUrls.value.prev = response.prev_page_url
                    pageUrls.value.next = response.next_page_url
                    pageUrls.value.current = response.current_page
                    pageUrls.value.perPage = response.per_page
                    pageUrls.value.from = response.from
                    pageUrls.value.to = response.to
                    emitResponse(response.data)
                    emitPageMeta()

                }).catch(err => {

                })
            }
        }

        onMounted(() => {
            if (props.baseUrl && props.onMountedRequest) {
                request(props.baseUrl)
            }
        })


        return {
            pageUrls, request
        }
    }
}
</script>

<style scoped>

</style>
