<div class="notification-panel" id="pushNotificationArea">
    <button class="dropdown-toggle">
        <i class="fal fa-bell"></i>
        <span v-if="items.length > 0" class="count">@{{items.length}}</span>
    </button>
    <ul class="notification-dropdown">
        <div class="dropdown-box">
            <li v-for="(item, index) in items" @click.prevent="readAt(item.id, item.description.link)">
                <a class="dropdown-item" href="javascript:void(0)">
                    <i class="fal fa-bell"></i>
                    <div class="text">
                        <p>@{{item.description.text}}</p>
                        <span v-cloak class="time">@{{ item.formatted_date }}</span>
                    </div>
                </a>
            </li>
        </div>
        <div class="clear-all fixed-bottom">
            <a href="javascript:void(0)" v-if="items.length > 0" @click.prevent="readAll">@lang('Clear all')</a>
            <a href="javascript:void(0)" v-if="items.length == 0"
               @click.prevent="readAll">@lang('You have no notifications')</a>
        </div>
    </ul>
</div>
@push('script')
    <script>
        'use strict';
        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: {
                items: [],
            },
            mounted() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('user.push.notification.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAt', 0) }}";
                    url = url.replace(/.$/, id);
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.getNotifications();
                                if (link != '#') {
                                    window.location.href = link
                                }
                            }
                        })
                },
                readAll() {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAll') }}";
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.items = [];
                            }
                        })
                },
                pushNewItem() {
                    let app = this;
                    // Pusher.logToConsole = true;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });
                    let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserNotification', function (data) {
                        app.items.unshift(data.message);
                    });
                    channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                        app.getNotifications();
                    });
                }
            }
        });
    </script>
@endpush

