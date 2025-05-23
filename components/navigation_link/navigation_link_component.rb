module Components
  module NavigationLink
    class NavigationLinkComponent < Middleman::Extension
      helpers do
        def navigation_link(opts)
          current_link_to opts[:text],
                          (opts[:link]).to_s,
                          class: 'transition-all flex items-center text-white
                                  px-2 lg:px-3 text-md lg:text-lg font-medium whitespace-nowrap
                                  motion-reduce:transition-none motion-reduce:transform-none'
        end
      end
    end
  end
end

::Middleman::Extensions.register(:navigation_link_component, Components::NavigationLink::NavigationLinkComponent)
