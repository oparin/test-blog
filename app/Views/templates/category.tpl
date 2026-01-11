{include file="header.tpl"}

<div class="category-page">
    <div class="category-info">
        <h1 class="category-title">{$category.name}</h1>
        <p class="category-description">{$category.description}</p>
    </div>

    <div class="sorting-controls">
        <span>Order by:</span>
        <a href="/category/{$category.id}?page=1&order=created_at"
           class="sort-btn {if $order_by == 'created_at'}active{/if}">
            <i class="far fa-calendar"></i> Date
        </a>
        <a href="/category/{$category.id}?page=1&order=views"
           class="sort-btn {if $order_by == 'views'}active{/if}">
            <i class="fas fa-eye"></i> Views
        </a>
    </div>

    {if $posts}
        <div class="posts-list">
            {foreach $posts as $post}
                <article class="post-item">
                    {if $post.image}
                        <div class="post-item-image">
                            <img src="{$post.image}" alt="{$post.title}">
                        </div>
                    {/if}
                    <div class="post-item-content">
                        <h2 class="post-item-title">
                            <a href="/post/{$post.id}">{$post.title}</a>
                        </h2>
                        <p class="post-item-excerpt">{$post.description}</p>
                        <div class="post-item-meta">
                            <span class="post-views">
                                <i class="fas fa-eye"></i> {$post.views} views
                            </span>
                            <span class="post-date">
                                <i class="far fa-calendar"></i> {$post.created_at|date_format:"%d.%m.%Y"}
                            </span>
                        </div>
                        <a href="/post/{$post.id}" class="btn btn-secondary">Read more</a>
                    </div>
                </article>
            {/foreach}
        </div>

        {if $total_pages > 1}
            <div class="pagination">
                {if $current_page > 1}
                    <a href="/category/{$category.id}?page={$current_page-1}&order={$order_by}"
                       class="pagination-item">
                        <i class="fas fa-chevron-left"></i> Back
                    </a>
                {/if}

                {for $page=1 to $total_pages}
                    <a href="/category/{$category.id}?page={$page}&order={$order_by}"
                       class="pagination-item {if $page == $current_page}active{/if}">
                        {$page}
                    </a>
                {/for}

                {if $current_page < $total_pages}
                    <a href="/category/{$category.id}?page={$current_page+1}&order={$order_by}"
                       class="pagination-item">
                        Next <i class="fas fa-chevron-right"></i>
                    </a>
                {/if}
            </div>
        {/if}
    {else}
        <div class="no-posts">
            <p>Posts not found</p>
            <a href="/" class="btn btn-primary">Home</a>
        </div>
    {/if}
</div>

{include file="footer.tpl"}