import _ from 'lodash'
import Vue from 'vue'
import VueRouter from 'vue-router'
import keyboard from 'keyboard'
import * as request from 'superagent'
import App from './App.vue'
import pathinfo from './helpers/pathinfo'

Vue.use(VueRouter)

// Global
window.keyboard = keyboard
window.objectPath = require('object-path')
window.disk = {}
window.config = {}
window.pathinfo = pathinfo
window.app = {
	path: '/',
	folder: '/',
	selected: '',
	files: [],
	focusable: [],
	status_text: ''
}

// Get config.json
request
	.get('config.json')
	.query({ format: 'json' })
	.end(function(error_config, config) {
		window.config = config.body
		if (!window.location.hash)
			window.location.hash = window.config.init_path

		// Get server data
		request
			.get(config.body.server)
			.query({ format: 'json' })
			.end(function(error_data, response) {
				// window.disk is the virtual hard disk
				window.disk = objectPath(response.body || JSON.parse(response.text))

				// Init Vue-Router
				const router = new VueRouter({
					base: __dirname,
					routes: [
						{ path: '/*' }
					]
				})

				// Init Vue
				new Vue({
					router,
					el: '#app',
					render: h => h(App)
				})
			})
	})