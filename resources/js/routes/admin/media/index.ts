import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\MediaController::index
* @see app/Http/Controllers/Admin/MediaController.php:19
* @route '/admin/media'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/media',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MediaController::index
* @see app/Http/Controllers/Admin/MediaController.php:19
* @route '/admin/media'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MediaController::index
* @see app/Http/Controllers/Admin/MediaController.php:19
* @route '/admin/media'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::index
* @see app/Http/Controllers/Admin/MediaController.php:19
* @route '/admin/media'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::index
* @see app/Http/Controllers/Admin/MediaController.php:19
* @route '/admin/media'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::index
* @see app/Http/Controllers/Admin/MediaController.php:19
* @route '/admin/media'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::index
* @see app/Http/Controllers/Admin/MediaController.php:19
* @route '/admin/media'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Admin\MediaController::library
* @see app/Http/Controllers/Admin/MediaController.php:49
* @route '/admin/media/library'
*/
export const library = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: library.url(options),
    method: 'get',
})

library.definition = {
    methods: ["get","head"],
    url: '/admin/media/library',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\MediaController::library
* @see app/Http/Controllers/Admin/MediaController.php:49
* @route '/admin/media/library'
*/
library.url = (options?: RouteQueryOptions) => {
    return library.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MediaController::library
* @see app/Http/Controllers/Admin/MediaController.php:49
* @route '/admin/media/library'
*/
library.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: library.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::library
* @see app/Http/Controllers/Admin/MediaController.php:49
* @route '/admin/media/library'
*/
library.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: library.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::library
* @see app/Http/Controllers/Admin/MediaController.php:49
* @route '/admin/media/library'
*/
const libraryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: library.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::library
* @see app/Http/Controllers/Admin/MediaController.php:49
* @route '/admin/media/library'
*/
libraryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: library.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::library
* @see app/Http/Controllers/Admin/MediaController.php:49
* @route '/admin/media/library'
*/
libraryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: library.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

library.form = libraryForm

/**
* @see \App\Http\Controllers\Admin\MediaController::store
* @see app/Http/Controllers/Admin/MediaController.php:79
* @route '/admin/media'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/media',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\MediaController::store
* @see app/Http/Controllers/Admin/MediaController.php:79
* @route '/admin/media'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MediaController::store
* @see app/Http/Controllers/Admin/MediaController.php:79
* @route '/admin/media'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::store
* @see app/Http/Controllers/Admin/MediaController.php:79
* @route '/admin/media'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::store
* @see app/Http/Controllers/Admin/MediaController.php:79
* @route '/admin/media'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Admin\MediaController::upload
* @see app/Http/Controllers/Admin/MediaController.php:110
* @route '/admin/media/upload'
*/
export const upload = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

upload.definition = {
    methods: ["post"],
    url: '/admin/media/upload',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\MediaController::upload
* @see app/Http/Controllers/Admin/MediaController.php:110
* @route '/admin/media/upload'
*/
upload.url = (options?: RouteQueryOptions) => {
    return upload.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MediaController::upload
* @see app/Http/Controllers/Admin/MediaController.php:110
* @route '/admin/media/upload'
*/
upload.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::upload
* @see app/Http/Controllers/Admin/MediaController.php:110
* @route '/admin/media/upload'
*/
const uploadForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: upload.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::upload
* @see app/Http/Controllers/Admin/MediaController.php:110
* @route '/admin/media/upload'
*/
uploadForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: upload.url(options),
    method: 'post',
})

upload.form = uploadForm

/**
* @see \App\Http\Controllers\Admin\MediaController::update
* @see app/Http/Controllers/Admin/MediaController.php:140
* @route '/admin/media/{media}'
*/
export const update = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/admin/media/{media}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Admin\MediaController::update
* @see app/Http/Controllers/Admin/MediaController.php:140
* @route '/admin/media/{media}'
*/
update.url = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { media: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { media: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            media: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        media: typeof args.media === 'object'
        ? args.media.id
        : args.media,
    }

    return update.definition.url
            .replace('{media}', parsedArgs.media.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MediaController::update
* @see app/Http/Controllers/Admin/MediaController.php:140
* @route '/admin/media/{media}'
*/
update.put = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::update
* @see app/Http/Controllers/Admin/MediaController.php:140
* @route '/admin/media/{media}'
*/
const updateForm = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::update
* @see app/Http/Controllers/Admin/MediaController.php:140
* @route '/admin/media/{media}'
*/
updateForm.put = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Admin\MediaController::destroy
* @see app/Http/Controllers/Admin/MediaController.php:161
* @route '/admin/media/{media}'
*/
export const destroy = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/media/{media}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\MediaController::destroy
* @see app/Http/Controllers/Admin/MediaController.php:161
* @route '/admin/media/{media}'
*/
destroy.url = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { media: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { media: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            media: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        media: typeof args.media === 'object'
        ? args.media.id
        : args.media,
    }

    return destroy.definition.url
            .replace('{media}', parsedArgs.media.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\MediaController::destroy
* @see app/Http/Controllers/Admin/MediaController.php:161
* @route '/admin/media/{media}'
*/
destroy.delete = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::destroy
* @see app/Http/Controllers/Admin/MediaController.php:161
* @route '/admin/media/{media}'
*/
const destroyForm = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\MediaController::destroy
* @see app/Http/Controllers/Admin/MediaController.php:161
* @route '/admin/media/{media}'
*/
destroyForm.delete = (args: { media: number | { id: number } } | [media: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const media = {
    index: Object.assign(index, index),
    library: Object.assign(library, library),
    store: Object.assign(store, store),
    upload: Object.assign(upload, upload),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default media