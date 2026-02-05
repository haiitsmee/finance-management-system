{{-- resources/views/components/letterhead-pdf.blade.php --}}
<div class="w-full flex flex-row items-center gap-5 letterhead">
<img class="image" src="{{ $imageData }}" alt="Logo">

<div class="">
    <div class="company-name">SEKAR SATRIA GROUP</div>
    <div class="business-division">Divisi {{ $division }}</div>
    <div class="business-name">{{ $businessName }}</div>
    
    <div class="address">
        <div>Office : 1. Karsan 04/06 Bojasari Kertek Wonosobo 2. Banjaran 05/04 Ngadimulyo Selomerto Wonosobo</div>
    </div>
    
    <div class="contact">
        WA&nbsp;&nbsp; : 081 393 591 057 - Telp : 0286 - 3305368
    </div>
</div>
</div>

<style>
.letterhead {
    display: flex;
    width: 100%;
    text-align: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #000;
    font-family: Arial, sans-serif;
}

.image {
    width: 10rem;
    height: 10rem;
}

.company-name {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 5px;
    text-transform: uppercase;
}

.business-division {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 8px;
    color: #555;
    text-transform: uppercase;
}

.business-name {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 12px;
    text-transform: uppercase;
    text-decoration: underline;
}

.address {
    font-size: 11px;
    line-height: 1.3;
    margin-bottom: 8px;
}

.address div {
    text-align: center;
}

.contact {
    font-size: 11px;
}

@media print {
    .letterhead {
        border-bottom: 2px solid #000 !important;
    }
    
    .company-name { font-size: 16px; }
    .business-name { font-size: 14px; }
    .business-division { font-size: 12px; }
    .address, .contact { font-size: 10px; }
}
</style>