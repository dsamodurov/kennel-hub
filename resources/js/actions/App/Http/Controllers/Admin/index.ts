import DashboardController from './DashboardController'
import NewsController from './NewsController'
import MediaController from './MediaController'
import PageController from './PageController'

const Admin = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    NewsController: Object.assign(NewsController, NewsController),
    MediaController: Object.assign(MediaController, MediaController),
    PageController: Object.assign(PageController, PageController),
}

export default Admin