const state = () => {
    return {
        menu: [
            {
                icon: 'HomeIcon',
                pageName: 'dashboard',
                title: 'Dashboard',
                subMenu: [
                    {
                        icon: '',
                        pageName: 'dashboard',
                        title: 'Dashboard'
                    }
                ]
            },
            {
                icon: 'HomeIcon',
                pageName: 'purchase.pricing',
                title: 'Purchase',
                subMenu: [
                    {
                        icon: '',
                        pageName: 'purchase.pricing',
                        title: 'Purchase A Node'
                    }
                ]
            },
            {
                icon: 'Share2Icon',
                pageName: 'team.genealogy',
                title: 'Genealogy',
                subMenu: [
                    {
                        icon: '',
                        pageName: 'team.genealogy',
                        title: 'Genealogy'
                    }
                ]
            },
            {
                icon: 'ArrowDownIcon',
                pageName: 'earnings.staking',
                title: 'Earning',
                subMenu: [
                    {
                        icon: '',
                        pageName: 'earnings.staking',
                        title: 'NSP'
                    },
                    {
                        icon: '',
                        pageName: 'earnings.level',
                        title: 'Royal Ranking'
                    },
                    {
                        icon: '',
                        pageName: 'earnings.xeon',
                        title: 'Xeon Profit'
                    },
                    {
                        icon: '',
                        pageName: 'earnings.node_pool',
                        title: 'Node Pool'
                    }

                ]
            },
            {
                icon: 'FileIcon',
                pageName: 'history.deposit',
                title: 'Deposit',
                subMenu: [
                    // {
                    //     icon: '',
                    //     pageName: 'deposit.btc',
                    //     title: 'BTC'
                    // },
                    // {
                    //     icon: '',
                    //     pageName: 'deposit.eth',
                    //     title: 'ETH'
                    // },
                    {
                        icon: '',
                        pageName: 'history.deposit',
                        title: 'Deposit History'
                    },
                    {
                        icon: '',
                        pageName: 'history.withdrawal',
                        title: 'Withdrawal History'
                    }
                ]
            },
            {
                icon: 'LogOutIcon',
                pageName: 'withdraw.accounts',
                title: 'Withdraw',
                subMenu: [
                    {
                        icon: '',
                        pageName: 'withdraw.accounts',
                        title: 'Withdraw Account'
                    },
                    {
                        icon: '',
                        pageName: 'withdraw.usdt',
                        title: 'Withdraw Funds'
                    }
                ]
            },
            {
                icon: 'UserIcon',
                pageName: 'account.profile',
                title: 'Profile',
                subMenu: [
                    {
                        icon: '',
                        pageName: 'account.profile',
                        title: 'Profile'
                    },
                    {
                        icon: '',
                        pageName: 'account.change.password',
                        title: 'Change Password'
                    }
                ]
            },


        ]
    }
}

// getters
const getters = {
    menu: state => state.menu
}

// actions
const actions = {}

// mutations
const mutations = {}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
