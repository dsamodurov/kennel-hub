import Auth from './Auth'
import Settings from './Settings'
import Admin from './Admin'
import NewsController from './NewsController'
import PageController from './PageController'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    Settings: Object.assign(Settings, Settings),
    Admin: Object.assign(Admin, Admin),
    NewsController: Object.assign(NewsController, NewsController),
    PageController: Object.assign(PageController, PageController),
}

export default Controllers