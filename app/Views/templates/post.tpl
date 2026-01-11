{include file="header.tpl"}

<article class="post-page">
    {if $post.image}
        <div class="post-header-image">
            <img src="{$post.image}" alt="{$post.title}">
        </div>
    {/if}

    <div class="post-header">
        <h1 class="post-title">{$post.title}</h1>

        <div class="post-meta-info">
            <span class="post-date">
                <i class="far fa-calendar"></i> Date: {$post.created_at|date_format:"%d.%m.%Y"}
            </span>
            <span class="post-views">
                <i class="fas fa-eye"></i> Views: {$post.views}
            </span>

            {if $post.categories}
                <div class="post-categories">
                    <i class="fas fa-tags"></i>
                    {foreach $post.categories as $cat}
                        <a href="/category/{$cat.id}" class="category-tag">{$cat.name}</a>
                    {/foreach}
                </div>
            {/if}
        </div>
    </div>

    <div class="post-description">
        <p><strong>{$post.description}</strong></p>
    </div>

    <div class="post-content">
        {$post.content}
    </div>
</article>

{if $similar_posts}
    <section class="similar-posts">
        <h2 class="section-title">Similar Posts</h2>
        <div class="posts-grid">
            {foreach $similar_posts as $post}
                <article class="post-card">
                    {if $post.image}
                        <div class="post-image">
                            <img src="{$post.image}" alt="{$post.title}">
                        </div>
                    {/if}
                    <div class="post-content">
                        <h3 class="post-title">{$post.title}</h3>
                        <p class="post-excerpt">{$post.description|truncate:100}</p>
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
    </section>
{/if}

{include file="footer.tpl"}