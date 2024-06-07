 @php
    if(!empty($feedbackEmail)){
        $email_to = explode(",",$feedbackEmail->email_to);
        $email_cc = explode(",",$feedbackEmail->email_cc);
    }
@endphp
<div class="row gx-5 align-items-center">
    <div class="row col-md-6 col-name mt-5" >   
        <label for="email_to" class="col-md-2 form-label " >To:<span class="red_text"><b>*</b></span></label>
        <select id="email_to" multiple name="email_tos[]" class="col-md-5 form-control form-select  form-select-sm " >
        <option value="" disabled  class="text-center">--Choose user--</option>
            @forelse($users as $user)
            <option 
                value="{{ $user->email}}" 
                {{ (!empty(old('email_to')) && old('email_to') == $user->email) ? 'selected': ''}}
                @php
                    if(!empty($feedbackEmail))
                        $email_to = explode(",",$feedbackEmail->email_to);
                    else
                        $email_to = [];
                @endphp
                
                @foreach($email_to as $email)
                    {{ (isset($feedbackEmail) && $email == $user->email && empty(old('email_to'))) ? 'selected' : '' }} 
                @endforeach
                >
                {{ $user->email }}
            </option>
            @empty
                <option value="" disabled>No users available</option>
            @endforelse
        </select>
        @error('email_to')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
                
    <div class="row col-md-6 col-name mt-5" >  
        <label for="email_cc" class="col-md-2 form-label " >CC:<span class="red_text"><b>*</b></span></label>
        <select id="email_cc" multiple name="email_ccs[]" class="col-md-5 form-control form-select  form-select-sm " >
        <option value="" disabled class="text-center">--Choose user--</option>
            @forelse($users as $user)
            <option 
                value="{{ $user->email }}" 
                {{ (!empty(old('email_cc')) && old('email_cc') == $user->email) ? 'selected': ''}}
                @php
                    if(!empty($feedbackEmail) && !empty($feedbackEmail->email_cc))
                        $email_cc = explode(",",$feedbackEmail->email_cc);
                    else
                        $email_cc = [];
                @endphp
                
                @foreach($email_cc as $email)
                    {{ (isset($feedbackEmail) && $email == $user->email && empty(old('email_cc'))) ? 'selected' : '' }} 
                @endforeach

                >
                {{ $user->email }}
            </option>
            @empty
                <option value="" disabled>No users available</option>
            @endforelse
        </select>
        @error('email_cc')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    
    </div>
           
</div>
