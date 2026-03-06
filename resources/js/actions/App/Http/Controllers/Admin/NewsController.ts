import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\NewsController::index
* @see app/Http/Controllers/Admin/NewsController.php:20
* @route '/admin/news'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/news',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::index
* @see app/Http/Controllers/Admin/NewsController.php:20
* @route '/admin/news'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::index
* @see app/Http/Controllers/Admin/NewsController.php:20
* @route '/admin/news'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::index
* @see app/Http/Controllers/Admin/NewsController.php:20
* @route '/admin/news'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::index
* @see app/Http/Controllers/Admin/NewsController.php:20
* @route '/admin/news'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::index
* @see app/Http/Controllers/Admin/NewsController.php:20
* @route '/admin/news'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::index
* @see app/Http/Controllers/Admin/NewsController.php:20
* @route '/admin/news'
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
* @see \App\Http\Controllers\Admin\NewsController::create
* @see app/Http/Controllers/Admin/NewsController.php:79
* @route '/admin/news/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/news/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::create
* @see app/Http/Controllers/Admin/NewsController.php:79
* @route '/admin/news/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::create
* @see app/Http/Controllers/Admin/NewsController.php:79
* @route '/admin/news/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::create
* @see app/Http/Controllers/Admin/NewsController.php:79
* @route '/admin/news/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::create
* @see app/Http/Controllers/Admin/NewsController.php:79
* @route '/admin/news/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::create
* @see app/Http/Controllers/Admin/NewsController.php:79
* @route '/admin/news/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::create
* @see app/Http/Controllers/Admin/NewsController.php:79
* @route '/admin/news/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Admin\NewsController::store
* @see app/Http/Controllers/Admin/NewsController.php:87
* @route '/admin/news'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/news',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::store
* @see app/Http/Controllers/Admin/NewsController.php:87
* @route '/admin/news'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::store
* @see app/Http/Controllers/Admin/NewsController.php:87
* @route '/admin/news'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::store
* @see app/Http/Controllers/Admin/NewsController.php:87
* @route '/admin/news'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::store
* @see app/Http/Controllers/Admin/NewsController.php:87
* @route '/admin/news'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Admin\NewsController::edit
* @see app/Http/Controllers/Admin/NewsController.php:134
* @route '/admin/news/{news}/edit'
*/
export const edit = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/news/{news}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::edit
* @see app/Http/Controllers/Admin/NewsController.php:134
* @route '/admin/news/{news}/edit'
*/
edit.url = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { news: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { news: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            news: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        news: typeof args.news === 'object'
        ? args.news.id
        : args.news,
    }

    return edit.definition.url
            .replace('{news}', parsedArgs.news.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::edit
* @see app/Http/Controllers/Admin/NewsController.php:134
* @route '/admin/news/{news}/edit'
*/
edit.get = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::edit
* @see app/Http/Controllers/Admin/NewsController.php:134
* @route '/admin/news/{news}/edit'
*/
edit.head = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::edit
* @see app/Http/Controllers/Admin/NewsController.php:134
* @route '/admin/news/{news}/edit'
*/
const editForm = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::edit
* @see app/Http/Controllers/Admin/NewsController.php:134
* @route '/admin/news/{news}/edit'
*/
editForm.get = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::edit
* @see app/Http/Controllers/Admin/NewsController.php:134
* @route '/admin/news/{news}/edit'
*/
editForm.head = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Admin\NewsController::update
* @see app/Http/Controllers/Admin/NewsController.php:155
* @route '/admin/news/{news}'
*/
export const update = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/admin/news/{news}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::update
* @see app/Http/Controllers/Admin/NewsController.php:155
* @route '/admin/news/{news}'
*/
update.url = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { news: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { news: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            news: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        news: typeof args.news === 'object'
        ? args.news.id
        : args.news,
    }

    return update.definition.url
            .replace('{news}', parsedArgs.news.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::update
* @see app/Http/Controllers/Admin/NewsController.php:155
* @route '/admin/news/{news}'
*/
update.post = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::update
* @see app/Http/Controllers/Admin/NewsController.php:155
* @route '/admin/news/{news}'
*/
const updateForm = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::update
* @see app/Http/Controllers/Admin/NewsController.php:155
* @route '/admin/news/{news}'
*/
updateForm.post = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, options),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Admin\NewsController::destroy
* @see app/Http/Controllers/Admin/NewsController.php:208
* @route '/admin/news/{news}'
*/
export const destroy = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/news/{news}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::destroy
* @see app/Http/Controllers/Admin/NewsController.php:208
* @route '/admin/news/{news}'
*/
destroy.url = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { news: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { news: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            news: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        news: typeof args.news === 'object'
        ? args.news.id
        : args.news,
    }

    return destroy.definition.url
            .replace('{news}', parsedArgs.news.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::destroy
* @see app/Http/Controllers/Admin/NewsController.php:208
* @route '/admin/news/{news}'
*/
destroy.delete = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::destroy
* @see app/Http/Controllers/Admin/NewsController.php:208
* @route '/admin/news/{news}'
*/
const destroyForm = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::destroy
* @see app/Http/Controllers/Admin/NewsController.php:208
* @route '/admin/news/{news}'
*/
destroyForm.delete = (args: { news: number | { id: number } } | [news: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

/**
* @see \App\Http\Controllers\Admin\NewsController::restore
* @see app/Http/Controllers/Admin/NewsController.php:215
* @route '/admin/news/{news}/restore'
*/
export const restore = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: restore.url(args, options),
    method: 'post',
})

restore.definition = {
    methods: ["post"],
    url: '/admin/news/{news}/restore',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::restore
* @see app/Http/Controllers/Admin/NewsController.php:215
* @route '/admin/news/{news}/restore'
*/
restore.url = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { news: args }
    }

    if (Array.isArray(args)) {
        args = {
            news: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        news: args.news,
    }

    return restore.definition.url
            .replace('{news}', parsedArgs.news.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::restore
* @see app/Http/Controllers/Admin/NewsController.php:215
* @route '/admin/news/{news}/restore'
*/
restore.post = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: restore.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::restore
* @see app/Http/Controllers/Admin/NewsController.php:215
* @route '/admin/news/{news}/restore'
*/
const restoreForm = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: restore.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::restore
* @see app/Http/Controllers/Admin/NewsController.php:215
* @route '/admin/news/{news}/restore'
*/
restoreForm.post = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: restore.url(args, options),
    method: 'post',
})

restore.form = restoreForm

/**
* @see \App\Http\Controllers\Admin\NewsController::forceDestroy
* @see app/Http/Controllers/Admin/NewsController.php:223
* @route '/admin/news/{news}/force'
*/
export const forceDestroy = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDestroy.url(args, options),
    method: 'delete',
})

forceDestroy.definition = {
    methods: ["delete"],
    url: '/admin/news/{news}/force',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\NewsController::forceDestroy
* @see app/Http/Controllers/Admin/NewsController.php:223
* @route '/admin/news/{news}/force'
*/
forceDestroy.url = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { news: args }
    }

    if (Array.isArray(args)) {
        args = {
            news: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        news: args.news,
    }

    return forceDestroy.definition.url
            .replace('{news}', parsedArgs.news.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\NewsController::forceDestroy
* @see app/Http/Controllers/Admin/NewsController.php:223
* @route '/admin/news/{news}/force'
*/
forceDestroy.delete = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: forceDestroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::forceDestroy
* @see app/Http/Controllers/Admin/NewsController.php:223
* @route '/admin/news/{news}/force'
*/
const forceDestroyForm = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: forceDestroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\NewsController::forceDestroy
* @see app/Http/Controllers/Admin/NewsController.php:223
* @route '/admin/news/{news}/force'
*/
forceDestroyForm.delete = (args: { news: string | number } | [news: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: forceDestroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

forceDestroy.form = forceDestroyForm

const NewsController = { index, create, store, edit, update, destroy, restore, forceDestroy }

export default NewsController