module Components
  module PostCard
    class PostCardComponent < Middleman::Extension
      helpers do
        def post_card(opts)
          post = opts[:post]
          title = post.title

          concat(
            content_tag(:article, class: 'max-w-2xl py-6 px-6 sm:py-12 sm:px-12 bg-white m-auto text-lg mb-12') do
              post_title(title) + post_publish_date(post) + post_image(post) +
              content_tag(:div, post.data.excerpt.to_s, class: 'prose mb-2') + read_more(post)
            end
          )
        end

        def post_image(post)
          return '' unless post.data.image
          image_tag(post.data.image, alt: post.title, class: 'w-full block mb-2', loading: 'lazy')
        end

        def post_title(title)
          content_tag(:h2, title, class: 'text-3xl font-bold mb-2')
        end

        def post_publish_date(post)
          content_tag(:div, local_date_time(post.date), class: 'text-gray-500 text-sm mb-2')
        end

        def read_more(post)
          button(text: 'Read More', link: true,
                 href: "/blog/#{post.slug}/", type: :primary,
                 html: { class: 'inline-block' })
        end
      end
    end
  end
end

::Middleman::Extensions.register(:post_card_component, Components::PostCard::PostCardComponent)
