<template>
    <div class="card-footer">
        <div class="row">
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center pagination-md">
                    <!-- First -->
                    <li :class="{ disabled: pageUrls.current === 1 }" class="page-item">
                        <a class="page-link" href="javascript:void(0)" @click="goToPage(1)">«</a>
                    </li>

                    <!-- Prev -->
                    <li :class="{ disabled: !pageUrls.prev }" class="page-item">
                        <a class="page-link" href="javascript:void(0)" @click="request(pageUrls.prev)">‹</a>
                    </li>

                    <!-- Page Numbers -->
                    <li v-for="p in visiblePages" :key="p"
                        :class="{ active: Number(p) === Number(pageUrls.current), disabled: p === '...' }"
                        class="page-item">
                        <a class="page-link" href="javascript:void(0)"
                           @click="p !== '...' && goToPage(p)">
                            {{ p }}
                        </a>
                    </li>

                    <!-- Next -->
                    <li :class="{ disabled: !pageUrls.next }" class="page-item">
                        <a class="page-link" href="javascript:void(0)" @click="request(pageUrls.next)">›</a>
                    </li>

                    <!-- Last -->
                    <li :class="{ disabled: pageUrls.current === pageUrls.lastPage }" class="page-item">
                        <a class="page-link" href="javascript:void(0)" @click="goToPage(pageUrls.lastPage)">»</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import { computed, ref } from "vue";
import axios from "axios";

export default {
    name: "AdminPaginator",
    props: {
        baseUrl: { type: String, required: true },
        method: { type: String, default: "get" },
        payload: { type: Object, default: () => ({}) },
        onMountedRequest: { type: Boolean, default: true },
    },
    emits: ["responseData", "pageMeta"],
    setup(props, { emit }) {
        const pageUrls = ref({
            first: null, prev: null, next: null, current: 1,
            last: null, perPage: null, from: null, to: null, lastPage: null,
        });

        function emitPageMeta() {
            emit("pageMeta", pageUrls.value);
        }

        function request(url) {
            if (!url) return;

            const method = props.method.toLowerCase();

            const req = method === "post"
                ? axios.post(url, props.payload || {})
                : axios.get(url);

            req.then(res => {
                const r = res.data;
                pageUrls.value = {
                    first: r.first_page_url,
                    prev: r.prev_page_url,
                    next: r.next_page_url,
                    current: r.current_page,
                    last: r.last_page_url,
                    perPage: r.per_page,
                    from: r.from,
                    to: r.to,
                    lastPage: r.last_page,
                };
                emit("responseData", r);
                emitPageMeta();
            }).catch(err => {
                console.error("Pagination request failed:", err);
            });
        }

        function goToPage(page) {
            if (!page || page === "...") return;

            const url = new URL(props.baseUrl, window.location.origin);
            url.searchParams.set("page", page);

            request(url.toString());
        }

        const visiblePages = computed(() => {
            const current = pageUrls.value.current || 1;
            const last = pageUrls.value.lastPage || 1;
            const delta = 2;
            const pages = [];

            let start = Math.max(2, current - delta);
            let end = Math.min(last - 1, current + delta);

            if (start > 2) pages.unshift("...");
            for (let i = start; i <= end; i++) pages.push(i);
            if (end < last - 1) pages.push("...");

            return [1, ...pages, last];
        });

        if (props.baseUrl && props.onMountedRequest) {
            request(props.baseUrl);
        }

        return { pageUrls, request, goToPage, visiblePages };
    },
};
</script>
