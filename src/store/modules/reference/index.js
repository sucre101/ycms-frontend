import AppList from './app/app_list'
import PageMain from './page/page'

export default {
  getters: {

    reference() {
      return {

        empty: [],

        apps: AppList,

        pages: PageMain,

        shop_list: [

          {
            title: 'This is list your shops',
            description: 'You can use ore update ...',
            video: 'link to video'
          }

        ],

        shop_settings: [

          {
            title: 'This is page where ... your shop settings',
            description: '... djklasjdlkasjdklajlsdjkasjlkdja'
          }

        ],

        module_list: [

          {
            title: 'This is list your shops',
            description: 'You can use ore update ...',
            video: 'link to video'
          }

        ]


      }
    }

  }
}
