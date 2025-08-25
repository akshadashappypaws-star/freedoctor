<div id="auto-reply" class="mb-5">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Auto Text/Template/ChatGPT Reply</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.whatsapp.auto-replies.store') }}">
                @csrf
                <div class="form-group">
                    <label for="keyword">Keyword or Phrase</label>
                    <input type="text" class="form-control" id="keyword" name="keyword" required>
                </div>
                <div class="form-group">
                    <label for="reply_type">Reply Type</label>
                    <select class="form-control" id="reply_type" name="reply_type" required onchange="toggleGptPrompt()">
                        <option value="text">Text</option>
                        <option value="template">Template</option>
                        <option value="gpt">ChatGPT (Advanced)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reply_content">Reply Content</label>
                    <textarea class="form-control" id="reply_content" name="reply_content" rows="3"></textarea>
                </div>
                <div class="form-group" id="gpt_prompt_group" style="display:none;">
                    <label for="gpt_prompt">ChatGPT Prompt</label>
                    <textarea class="form-control" id="gpt_prompt" name="gpt_prompt" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-warning">Save Auto Reply</button>
            </form>
            <script>
            function toggleGptPrompt() {
                var type = document.getElementById('reply_type').value;
                document.getElementById('gpt_prompt_group').style.display = (type === 'gpt') ? '' : 'none';
                document.getElementById('reply_content').required = (type !== 'gpt');
                document.getElementById('gpt_prompt').required = (type === 'gpt');
            }
            document.getElementById('reply_type').addEventListener('change', toggleGptPrompt);
            </script>
            <div class="mt-4">
                <h5>Configured Auto Replies</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Type</th>
                            <th>Reply/Prompt</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($autoReplies ?? [] as $ar)
                        <tr>
                            <td>{{ $ar->keyword }}</td>
                            <td>{{ ucfirst($ar->reply_type) }}</td>
                            <td>
                                @if($ar->reply_type === 'gpt')
                                    <b>Prompt:</b> {{ $ar->gpt_prompt }}
                                @else
                                    {{ $ar->reply_content }}
                                @endif
                            </td>
                            <td>{{ $ar->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No auto replies configured yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <h5>Conversation History</h5>
                <form method="GET" action="{{ route('admin.whatsapp.conversation-history') }}" class="form-inline mb-2">
                    <input type="text" name="phone" class="form-control mr-2" placeholder="Enter phone to filter" value="{{ request('phone') }}">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Reply</th>
                            <th>Type</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversations ?? [] as $conv)
                        <tr>
                            <td>{{ $conv->phone }}</td>
                            <td>{{ $conv->message }}</td>
                            <td>{{ $conv->reply }}</td>
                            <td>{{ ucfirst($conv->reply_type) }}</td>
                            <td>{{ $conv->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No conversation history available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
