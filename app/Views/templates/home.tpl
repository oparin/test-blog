{include file="header.tpl"}

<h1 class="page-title">Welcome to Test Blog!</h1>

{if $categories}
    {foreach $categories as $category}
        <section class="category-section">
            <div class="category-header">
                <h2 class="category-title">{$category.name}</h2>
                <p class="category-description">{$category.description}</p>
                <a href="/category/{$category.id}" class="btn btn-primary">
                    All posts ({$category.post_count})
                </a>
            </div>

            {if $category.latest_posts}
                <div class="posts-grid">
                    {foreach $category.latest_posts as $post}
                        <article class="post-card">
                            {if $post.image}
                                <div class="post-image">
                                    <img src="{$post.image}" alt="{$post.title}">
                                </div>
                            {/if}
                            <div class="post-content">
                                <h3 class="post-title">{$post.title}</h3>
                                <p class="post-excerpt">{$post.description|truncate:150}</p>
                                <div class="post-meta">
                                    <span class="post-views">
                                        <i class="fas fa-eye"></i> {$post.views}
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
            {else}
                <p class="no-posts">Posts not found</p>
            {/if}
        </section>
    {/foreach}
{else}
    <div class="no-categories">
        <p>Categories not found</p>
    </div>
{/if}

{include file="footer.tpl"}