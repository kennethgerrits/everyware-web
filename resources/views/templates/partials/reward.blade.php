<div class="modal fade" id="rewardModal" tabindex="-1" role="dialog" aria-labelledby="rewardModelLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rewardModelLabel">Beloning beheren</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">Beloning link</label>
                    <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal"
                            data-header="Youtube link beloning"
                            data-text="Zet in dit veld de Youtube link. Parameters in dit veld worden niet opgeslagen."></button>
                    <input class="form-control input-field" required type="text" name="reward" id="modal_reward">
                </div>
                <div class="form-group">
                    <label for="name">Starttijd</label>
                    <button class="far fa-question-circle info-button" data-toggle="modal" data-target="#infoModal"
                            data-header="Starttijd aangeven Youtube video"
                            data-text="Let op! Zorg ervoor dat de starttijd korter is dan de videoduur, anders speelt de video niet goed af!"></button>
                    <br>
                    <input class="form-control time-input input-field" min="0" max="100" type="number" name="start_min" id="start_min"
                           value="0"> :
                    <input class="form-control time-input input-field" min="0" max="59" type="number" name="start_sec" id="start_sec"
                           value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="reward-save-btn">Opslaan</button>
            </div>
        </div>
    </div>
</div>
