module Components
  module BeerCard
    class BeerCardComponent < Middleman::Extension
      helpers do
        def beer_card(opts)
          beer = opts[:beer]

          concat(
            link_to("/beers/#{beer.slug}/", class: 'bg-neutral relative') do
              beer_image(beer) +
              content_tag(:div, class: classes) do
                content_tag(:h2, beer.title, class: 'text-white text-xl') +
                content_tag(:div, beer.style, class: 'text-green-default')
              end
            end
          )
        end

        # def beer_image(beer)
        #   image_tag(beer.image.url(fm: :webp, h: 540, w: 540), alt: beer.title, class: 'block w-full',
        #                                                        width: '540', height: '540', loading: 'lazy')
        # end

        def beer_image(beer)
          image_url = beer.image.url

          image_tag("#{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max", 
                    alt: beer.title, 
                    class: 'block w-full', 
                    height: '540',
                    width: '540',
                    loading: 'lazy',
                    srcset: "#{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max&dpr=1 1x,
                             #{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max&dpr=2 2x,
                             #{image_url}?fm=webp&h=540&w=540&auto=enhance&fit=max&dpr=3 3x"
          )
        end

        def classes
          'transition-all opacity-0 w-full h-full bg-black absolute top-0 left-0
           flex flex-col justify-center text-center p-10 hover:opacity-100
           motion-reduce:transition-none motion-reduce:transform-none'
        end
      end
    end
  end
end

::Middleman::Extensions.register(:beer_card_component, Components::BeerCard::BeerCardComponent)
