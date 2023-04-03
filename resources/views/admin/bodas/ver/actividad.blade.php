<div class="col-sm-12 col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                Historial del evento
            </div>
        </div>
        <div class="card-body row" style="max-height: 400px;overflow-y:auto">
            <div class="activity-feed">
                <table class="table table-responsive">
                    <tbody>
                        @foreach($boda->activities as $key => $act)
                          <tr class="feed-item">
                              <td style="padding-left: 0 !important"><div class="text">{!! $act->description !!}</div></td>
                              <td><div class="date"><i class="far fa-clock"></i> {!! \Carbon\Carbon::parse($act->created_at)->diffForHumans() !!}</div></td>
                          </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .activity-feed {
      padding: 15px;
    }
    .activity-feed .feed-item {
      position: relative;
      padding-bottom: 15px;
    }
    .activity-feed .feed-item:last-child {
      border-color: transparent;
    }
    /* .activity-feed .feed-item:after {
      content: "";
      display: block;
      position: absolute;
      top: 0;
      left: -6px;
      width: 10px;
      height: 10px;
      border-radius: 6px;
      background: #fff;
      border: 1px solid #f37167;
    } */
    .activity-feed .feed-item .date {
      position: relative;
      text-align: right;
      color: #8c96a3;
      font-size: 10.5px;
    }
    .activity-feed .feed-item .text {
      position: relative;
      top: -3px;
      font-size: 13px;
      font-weight: 300;
    }
</style>