<template>
    <img class="geneaology-img" @click="showTree" :src="getIcon" :id="id" @mouseover="showMemberPopover">
</template>

<script>
export default {
    name: "GeneologyImg",
    data() {
        return {
            id: null,
            treeIcons: {
                blank: '/images/blank.png',
                active: '/img/1.png',
                registered: '/images/register.png'

            }
        }
    },
    props: {
        level: {
            type: Object,
            default: null
        },
        isRoot: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        showTree() {
            if (this.level && !this.isRoot) {
                this.$inertia.visit(route('partners.geneaology', [this.level.user.id]), {
                        preserveScroll: true
                    }
                )
            }
        },
        show() {
            if (this.id) {
                $('#' + this.id).popover('toggle')
            }
        },
        showMemberPopover() {
            if (this.level) {
                let el = this.level.user.id
                this.id = el
                $('#' + el).popover({
                    container: 'body',
                    toggle: 'popover',
                    title: this.level.user.name,
                    sanitize: false,
                    content: '<div class="table-responsive mb-0">' +
                        '<table class="table table-hover table-striped table-dashboard-two table-bordered mb-0">' +
                        '<tbody>' +
                        '<tr>' +
                        `<td class="text-nowrap">Sponsor</td><td class="text-nowrap text-warning">${this.level.sponsor ? this.level.sponsor.name : 'N/A'}</td>` +
                        `<td class="text-nowrap">User Name</td><td class="text-nowrap text-warning">${this.level.user.name}</td>` +
                        '</tr>' +
                        '<tr>' +
                        `<td class="text-nowrap">Joined On</td><td class="text-nowrap text-warning">${this.level.user.created_at}</td>` +
                        `<td class="text-nowrap">Team</td><td class="text-nowrap text-warning">${this.level.user.team.left + this.level.user.team.right}</td>` +
                        '</tr>' +
                        '<tr>' +
                        `<td class="text-nowrap">Left Total/Active</td><td class="text-nowrap text-warning">${this.level.user.team.left + ' / ' + this.level.user.team.active_left}</td>` +
                        `<td class="text-nowrap">Right Total/Active</td><td class="text-nowrap text-warning">${this.level.user.team.right + ' / ' + this.level.user.team.active_right}</td>` +
                        '</tr>' +
                        '<tr>' +
                        `<td class="text-nowrap">Left Business</td><td class="text-nowrap text-warning">$${this.level.user.user_business ? this.level.user.user_business.left_usd : '0.00'}</td>` +
                        `<td class="text-nowrap">Right Business</td><td class="text-nowrap text-warning">$${this.level.user.user_business ? this.level.user.user_business.right_usd : '0.00'}</td>` +
                        '</tr>' +

                        '</tbody>' +
                        '<div>',

                    html: true,
                    placement: 'auto',
                    trigger: 'hover',
                    template: '<div class="popover popover-head-primary geneaology" role="tooltip">' +
                        '<div class="arrow"></div>' +
                        '<h3 class="popover-header"></h3>' +
                        '<div class="popover-body">' +
                        '</div>' +
                        '</div>',
                    // delay: {"show": 500, "hide": 1000},
                    color: 'primary'

                })
                $('#' + el).popover('toggle')
            }

        }
    },
    computed: {
        getIcon() {
            if (this.level) {
                // return this.treeIcons.registered
                if (this.level.user.active_at) {
                    return this.treeIcons.active
                } else {
                    return this.treeIcons.registered
                }

            } else {
                return this.treeIcons.blank
            }
        },
    },
    mounted() {
        this.showMemberPopover()

    },
    beforeDestroy() {
        $('.popover').popover('hide')
    }
}
</script>

<style scoped>

</style>
