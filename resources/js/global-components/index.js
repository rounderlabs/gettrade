import * as featherIcons from '@zhuowenli/vue-feather-icons'
import {library} from '@fortawesome/fontawesome-svg-core'
import {faSpinner, faUser} from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon, FontAwesomeLayers, FontAwesomeLayersText} from '@fortawesome/vue-fontawesome'

library.add(faSpinner, faUser)

export default app => {

    app.component('font-awesome-icon', FontAwesomeIcon)
    app.component('font-awesome-layers', FontAwesomeLayers)
    app.component('font-awesome-layers-text', FontAwesomeLayersText)

    for (const [key, icon] of Object.entries(featherIcons)) {
        icon.props.size.default = '24'
        app.component(key, icon)
    }
}
