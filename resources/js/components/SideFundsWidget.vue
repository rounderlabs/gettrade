<template>
    <div
        class="report-box-4 w-full h-full grid grid-cols-12 gap-6 xl:absolute -mt-8 xl:mt-0 pb-6 xl:pb-0 top-0 right-0 z-30 xl:z-auto"
    >
        <div class="col-span-12 xl:col-span-3 xl:col-start-10 xl:pb-16 z-30">
            <div class="h-full flex flex-col">
                <div class="box p-5 mt-6 bg-theme-25 intro-x">
                    <div class="flex flex-wrap gap-3">
                        <div class="mr-auto">
                            <div
                                class="text-white text-opacity-70 flex items-center leading-3"
                            >
                                AVAILABLE FUNDS
                                <Tippy
                                    tag="div"
                                    content="Total value of your sales: $158.409.416">
                                    <AlertCircleIcon class="w-4 h-4 ml-1.5"/>
                                </Tippy>
                            </div>
                            <div class="text-white relative text-2xl font-medium leading-5 pl-3.5 mt-3.5">
                    <span
                        class="absolute text-lg font-bold top-0 left-0 -mt-1.5"
                    >$</span
                    >
                                47,578.77
                            </div>
                        </div>
                        <a
                            class="flex items-center justify-center w-12 h-12 rounded-full bg-theme-26 dark:bg-dark-1 hover:bg-opacity-80 text-white"
                            href=""
                        >
                            <PlusIcon class="w-6 h-6"/>
                        </a>
                    </div>
                </div>
                <div class="report-box-4__content xl:min-h-0 intro-x">
                    <div class="max-h-full xl:overflow-y-auto box mt-5">
                        <div
                            class="bg-white dark:bg-dark-3 xl:sticky top-0 px-5 pt-5 pb-6"
                        >
                            <div class="flex items-center">
                                <div class="text-lg font-medium truncate mr-5">
                                    Summary Report
                                </div>
                                <a
                                    href=""
                                    class="ml-auto flex items-center text-theme-30 dark:text-theme-25"
                                >
                                    <RefreshCcwIcon class="w-4 h-4 mr-3"/>
                                    Refresh
                                </a>
                            </div>
                            <div
                                class="boxed-tabs nav nav-tabs justify-center border border-gray-400 border-dashed text-gray-600 dark:border-gray-700 dark:bg-dark-3 dark:text-gray-500 rounded-md p-1 mt-5 mx-auto"
                                role="tablist"
                            >
                                <a
                                    id="active-users-tab"
                                    data-toggle="tab"
                                    data-target="#active-users"
                                    href="javascript:;"
                                    class="btn flex-1 border-0 shadow-none py-1.5 px-2 active"
                                    role="tab"
                                    aria-controls="active-users"
                                    aria-selected="true"
                                >Weekly</a
                                >
                                <a
                                    id="inactive-users-tab"
                                    data-toggle="tab"
                                    data-target="#inactive-users"
                                    href="javascript:;"
                                    class="btn flex-1 border-0 shadow-none py-1.5 px-2"
                                    role="tab"
                                    aria-selected="false"
                                >Monthly</a
                                >
                            </div>
                        </div>
                        <div class="tab-content px-5 pb-5">
                            <div
                                id="active-users"
                                class="tab-pane active grid grid-cols-12 gap-y-6"
                                role="tabpanel"
                                aria-labelledby="active-users-tab"
                            >
                                <div
                                    class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-12"
                                >
                                    <div class="text-gray-600 dark:text-gray-500">
                                        Unpaid Loan
                                    </div>
                                    <div class="mt-1.5 flex items-center">
                                        <div class="text-lg">$155.430.000</div>
                                        <Tippy
                                            tag="div"
                                            class="text-theme-21 flex text-xs font-medium tooltip cursor-pointer ml-2"
                                            content="2% Lower than last month"
                                        >
                                            2%
                                            <ChevronDownIcon class="w-4 h-4 ml-0.5"/>
                                        </Tippy>
                                    </div>
                                </div>
                                <div
                                    class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-12"
                                >
                                    <div class="text-gray-600 dark:text-gray-500">
                                        Active Funding Partner
                                    </div>
                                    <div class="mt-1.5 flex items-center">
                                        <div class="text-lg">52 Partner</div>
                                        <Tippy
                                            tag="div"
                                            class="text-theme-20 flex text-xs font-medium tooltip cursor-pointer ml-2"
                                            content="0.1% Lower than last month"
                                        >
                                            49%
                                            <ChevronUpIcon class="w-4 h-4 ml-0.5"/>
                                        </Tippy>
                                    </div>
                                </div>
                                <div
                                    class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-12"
                                >
                                    <div class="text-gray-600 dark:text-gray-500">
                                        Paid Installment
                                    </div>
                                    <div class="mt-1.5 flex items-center">
                                        <div class="text-lg">$75.430.000</div>
                                        <Tippy
                                            tag="div"
                                            class="text-theme-20 flex text-xs font-medium tooltip cursor-pointer ml-2"
                                            content="49% Higher than last month"
                                        >
                                            36%
                                            <ChevronUpIcon class="w-4 h-4 ml-0.5"/>
                                        </Tippy>
                                    </div>
                                </div>
                                <div
                                    class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-12"
                                >
                                    <div class="text-gray-600 dark:text-gray-500">
                                        Success Payment
                                    </div>
                                    <div class="mt-1.5 flex items-center">
                                        <div class="text-lg">100%</div>
                                    </div>
                                </div>
                                <div
                                    class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-12"
                                >
                                    <div class="text-gray-600 dark:text-gray-500">
                                        Waiting For Disbursement
                                    </div>
                                    <div class="mt-1.5 flex items-center">
                                        <div class="text-lg">0</div>
                                    </div>
                                </div>
                                <div
                                    class="col-span-12 sm:col-span-6 md:col-span-4 xl:col-span-12"
                                >
                                    <div class="text-gray-600 dark:text-gray-500">
                                        Unpaid Loan
                                    </div>
                                    <div class="mt-1.5 flex items-center">
                                        <div class="text-lg">$21.430.000</div>
                                        <Tippy
                                            tag="div"
                                            class="text-theme-21 flex text-xs font-medium tooltip cursor-pointer ml-2"
                                            content="2% Lower than last month"
                                        >
                                            23%
                                            <ChevronDownIcon class="w-4 h-4 ml-0.5"/>
                                        </Tippy>
                                    </div>
                                </div>
                                <button
                                    class="btn btn-outline-secondary col-span-12 border-gray-400 border-dashed relative justify-start mb-2"
                                >
                                    <div class="truncate mr-5">My Portfolio Details</div>
                                    <span
                                        class="w-8 h-8 absolute flex justify-center items-center right-0 top-0 bottom-0 my-auto ml-auto mr-0.5"
                                    >
                        <ArrowRightIcon class="w-4 h-4"/>
                      </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SideFundsWidget"
}
</script>

<style scoped>

</style>
