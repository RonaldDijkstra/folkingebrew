module Components
  module PostCard
    class PostCardComponent < Middleman::Extension
      helpers do 
        def post_card(opts)
          post = opts[:post]
          image = post.index_image.url(fm: "png")

          concat(
            content_tag(:article, class: "max-w-2xl py-6 px-6 sm:py-12 sm:px-12 bg-white m-auto text-lg mb-12") do
              link_to("/blog/#{post.slug}") do 
                content_tag(:h2, post.title, class: "text-3xl font-bold mb-2") +
                content_tag(:div, "#{post.publish_date}", class: "text-gray-500 text-sm mb-2") +
                image_tag(image, alt: post.title, class: "w-full block mb-2", loading: "lazy") +
                content_tag(:div, "#{post.excerpt}", class: "prose mb-2") +
                button(text: "Read More", link: true, href: "/blog/#{post.slug}", type: :primary, html: { class: "inline-block" } ) 
              end
            end 
          )
        end
      end
    end
  end
end

::Middleman::Extensions.register(:post_card_component, Components::PostCard::PostCardComponent)
